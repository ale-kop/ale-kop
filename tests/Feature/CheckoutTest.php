<?php

use App\Actions\Checkout\SyncOrderWithPayment;
use App\Actions\Courses\GrantCourseAccess;
use App\Mail\OrderApprovedMail;
use App\Models\Course;
use App\Models\CourseAccess;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

const VALID_CPF = '529.982.247-25';

beforeEach(function () {
    config([
        'services.mercadopago.access_token' => 'TEST-token',
        'services.mercadopago.public_key' => 'TEST-public',
        'services.mercadopago.webhook_secret' => 'segredo',
    ]);

    $this->course = Course::query()->create([
        'name' => 'Orçamentos e propostas comerciais pelo WhatsApp',
        'slug' => config('offers.whatsapp.course_slug'),
        'extra' => ['access' => 'paid', 'price' => 197.00],
    ]);

    Mail::fake();
});

function mpPayment(array $overrides = []): array
{
    return array_replace_recursive([
        'id' => 1234567890,
        'status' => 'approved',
        'status_detail' => 'accredited',
        'transaction_amount' => 197.00,
        'external_reference' => null,
    ], $overrides);
}

function cardCheckout(array $overrides = []): array
{
    return array_merge([
        'name' => 'Marina Lopes',
        'email' => 'Marina@Exemplo.com',
        'document' => VALID_CPF,
        'payment_method' => 'card',
        'token' => 'card-token',
        'payment_method_id' => 'visa',
        'installments' => 3,
    ], $overrides);
}

it('shows the offer page with the course price', function () {
    $this->get(route('offers.whatsapp'))
        ->assertOk()
        ->assertSee('Seu próximo orçamento sai de uma mensagem no WhatsApp')
        ->assertSee('R$ 197,00')
        ->assertSee(route('checkout.show', $this->course->slug));
});

it('shows the checkout for a paid course and 404s for a free one', function () {
    $this->get(route('checkout.show', $this->course->slug))
        ->assertOk()
        ->assertSee('Pagar R$ 197,00')
        ->assertSee('data-public-key="TEST-public"', false);

    Course::query()->create(['name' => 'Grátis', 'slug' => 'gratis']);
    $this->get(route('checkout.show', 'gratis'))->assertNotFound();
});

it('sends a student who already has the course to it, but lets an admin preview the checkout', function () {
    $student = User::factory()->create(['role' => 'student']);
    app(GrantCourseAccess::class)->handle($student, $this->course);

    $this->actingAs($student)->get(route('checkout.show', $this->course->slug))
        ->assertRedirect(route('courses.show', $this->course->slug));

    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->get(route('checkout.show', $this->course->slug))
        ->assertOk()
        ->assertSee('Pagar R$ 197,00');
});

it('approves a card payment, creates the account and grants the course', function () {
    Http::fake(['api.mercadopago.com/v1/payments' => fn ($request) => Http::response(mpPayment([
        'external_reference' => $request['external_reference'],
    ]), 201)]);

    $this->postJson(route('checkout.pay', $this->course->slug), cardCheckout())
        ->assertOk()
        ->assertJson(['status' => 'approved']);

    $order = Order::query()->sole();
    $user = User::query()->firstWhere('email', 'marina@exemplo.com');

    expect($order->status)->toBe('approved')
        ->and($order->paid_at)->not->toBeNull()
        ->and($order->user_id)->toBe($user->id)
        ->and($order->installments)->toBe(3)
        ->and($order->document)->toBe('52998224725')
        ->and($user->canAccessCourse($this->course))->toBeTrue();

    Http::assertSent(fn ($request) => $request->hasHeader('X-Idempotency-Key', $order->uuid)
        && $request['token'] === 'card-token'
        && $request['transaction_amount'] === 197.0
        && $request['payer']['identification']['number'] === '52998224725');

    Mail::assertQueued(OrderApprovedMail::class, fn ($mail) => $mail->passwordToken !== null);
});

it('returns a readable message when the card is rejected', function () {
    Http::fake(['api.mercadopago.com/v1/payments' => Http::response(mpPayment([
        'status' => 'rejected',
        'status_detail' => 'cc_rejected_insufficient_amount',
    ]), 201)]);

    $this->postJson(route('checkout.pay', $this->course->slug), cardCheckout())
        ->assertStatus(422)
        ->assertJsonPath('message', 'O cartão não tem limite suficiente. Tente outro cartão ou pague com Pix.');

    expect(Order::query()->sole()->status)->toBe('rejected');
    expect(CourseAccess::query()->count())->toBe(0);
    Mail::assertNothingQueued();
});

it('validates the CPF before calling Mercado Pago', function () {
    Http::fake();

    $this->postJson(route('checkout.pay', $this->course->slug), cardCheckout(['document' => '111.111.111-11']))
        ->assertStatus(422)
        ->assertJsonValidationErrors('document');

    Http::assertNothingSent();
});

it('creates a pending Pix order and shows the QR code page', function () {
    Http::fake(['api.mercadopago.com/v1/payments' => Http::response(mpPayment([
        'status' => 'pending',
        'status_detail' => 'pending_waiting_transfer',
        'date_of_expiration' => now()->addMinutes(30)->toIso8601String(),
        'point_of_interaction' => ['transaction_data' => [
            'qr_code' => '00020126PIXCOPIAECOLA',
            'qr_code_base64' => 'iVBORw0KGgo=',
        ]],
    ]), 201)]);

    $response = $this->postJson(route('checkout.pay', $this->course->slug), [
        'name' => 'Marina Lopes',
        'email' => 'marina@exemplo.com',
        'document' => VALID_CPF,
        'payment_method' => 'pix',
    ])->assertOk()->assertJson(['status' => 'pending']);

    Http::assertSent(fn ($request) => $request['payment_method_id'] === 'pix' && isset($request['date_of_expiration']));

    $this->get($response->json('redirect'))
        ->assertOk()
        ->assertSee('Pague com Pix')
        ->assertSee('00020126PIXCOPIAECOLA');

    expect(CourseAccess::query()->count())->toBe(0);
});

it('fulfils a Pix order once when the webhook confirms the payment', function () {
    $order = Order::query()->create([
        'course_id' => $this->course->id,
        'name' => 'Marina Lopes',
        'email' => 'marina@exemplo.com',
        'document' => '52998224725',
        'amount' => 197.00,
        'payment_method' => 'pix',
        'mp_payment_id' => '1234567890',
    ]);

    Http::fake(['api.mercadopago.com/v1/payments/1234567890' => Http::response(mpPayment([
        'external_reference' => $order->uuid,
    ]))]);

    $ts = '1700000000';
    $requestId = 'req-1';
    $signature = hash_hmac('sha256', "id:1234567890;request-id:{$requestId};ts:{$ts};", 'segredo');
    $headers = ['x-signature' => "ts={$ts},v1={$signature}", 'x-request-id' => $requestId];
    $url = route('webhooks.mercadopago').'?data.id=1234567890&type=payment';

    $this->postJson($url, ['type' => 'payment', 'data' => ['id' => '1234567890']], $headers)->assertNoContent();
    // Mercado Pago reenvia notificações: a segunda não pode liberar de novo nem reenviar o e-mail.
    $this->postJson($url, ['type' => 'payment', 'data' => ['id' => '1234567890']], $headers)->assertNoContent();

    expect($order->refresh()->status)->toBe('approved')
        ->and(User::query()->firstWhere('email', 'marina@exemplo.com')->canAccessCourse($this->course))->toBeTrue();

    Mail::assertQueuedCount(1);
});

it('rejects webhooks with an invalid signature', function () {
    Http::fake();

    $this->postJson(route('webhooks.mercadopago').'?data.id=1&type=payment', [], [
        'x-signature' => 'ts=1,v1=assinatura-falsa',
        'x-request-id' => 'req-1',
    ])->assertUnauthorized();

    Http::assertNothingSent();
});

it('does not grant access when the paid amount is lower than the order', function () {
    $order = Order::query()->create([
        'course_id' => $this->course->id,
        'name' => 'Marina Lopes',
        'email' => 'marina@exemplo.com',
        'amount' => 197.00,
        'payment_method' => 'pix',
    ]);

    app(SyncOrderWithPayment::class)->handle($order, mpPayment([
        'transaction_amount' => 1.00,
        'external_reference' => $order->uuid,
    ]));

    expect($order->refresh()->status)->toBe('pending');
    expect(CourseAccess::query()->count())->toBe(0);
});

it('links the purchase to an existing account without a password link', function () {
    $user = User::factory()->create(['email' => 'marina@exemplo.com', 'role' => 'student']);

    Http::fake(['api.mercadopago.com/v1/payments' => fn ($request) => Http::response(mpPayment([
        'external_reference' => $request['external_reference'],
    ]), 201)]);

    $this->postJson(route('checkout.pay', $this->course->slug), cardCheckout())->assertOk();

    expect(User::query()->count())->toBe(1)
        ->and($user->canAccessCourse($this->course))->toBeTrue();

    Mail::assertQueued(OrderApprovedMail::class, fn ($mail) => $mail->passwordToken === null);
});

it('suspends access when an approved payment is refunded', function () {
    Http::fake(['api.mercadopago.com/v1/payments' => fn ($request) => Http::response(mpPayment([
        'external_reference' => $request['external_reference'],
    ]), 201)]);

    $this->postJson(route('checkout.pay', $this->course->slug), cardCheckout())->assertOk();
    $order = Order::query()->sole();

    app(SyncOrderWithPayment::class)->handle($order, mpPayment([
        'status' => 'refunded',
        'external_reference' => $order->uuid,
    ]));

    expect($order->refresh()->status)->toBe('refunded')
        ->and($order->user->canAccessCourse($this->course))->toBeFalse();
});
