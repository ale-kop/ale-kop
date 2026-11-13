<?php

use App\Mail\ContactMail;
use App\Services\EmailDomainChecker;
use App\Services\FormTimeTrap;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
    // Sem rede nos testes: todo domínio é aceito
    app()->instance(EmailDomainChecker::class, new class extends EmailDomainChecker
    {
        public function isAcceptable(string $domain): bool
        {
            return true;
        }
    });
});

function contactPayload(array $extra = []): array
{
    return array_merge([
        'name' => 'Maria',
        'email' => 'maria@gmail.com',
        'message' => 'Olá, gostaria de falar sobre uma parceria.',
    ], $extra);
}

function sendContact(array $payload)
{
    return test()->postJson(route('contact.send'), $payload);
}

it('renders the contact form with a time token', function () {
    $this->withoutVite()
        ->get(route('contact.show'))
        ->assertOk()
        ->assertSee('name="form_token"', false);
});

it('queues the message when the token is old enough', function () {
    $token = app(FormTimeTrap::class)->issue();
    $this->travel(FormTimeTrap::MIN_SECONDS + 1)->seconds();

    sendContact(contactPayload(['form_token' => $token]))
        ->assertOk()
        ->assertJsonPath('type', 'success');

    Mail::assertQueued(ContactMail::class, 1);
});

it('silently drops a submission sent too fast', function () {
    $token = app(FormTimeTrap::class)->issue();

    sendContact(contactPayload(['form_token' => $token]))
        ->assertOk()
        ->assertJsonPath('type', 'success');

    Mail::assertNothingQueued();
});

it('silently drops a submission without the token', function () {
    sendContact(contactPayload())
        ->assertOk()
        ->assertJsonPath('type', 'success');

    Mail::assertNothingQueued();
});

it('silently drops a forged or tampered token', function () {
    sendContact(contactPayload(['form_token' => 'token-forjado']))
        ->assertOk();

    Mail::assertNothingQueued();
});

it('rejects a submission that fills the honeypot', function () {
    $token = app(FormTimeTrap::class)->issue();
    $this->travel(FormTimeTrap::MIN_SECONDS + 1)->seconds();

    sendContact(contactPayload(['form_token' => $token, 'website' => 'http://spam.example']))
        ->assertStatus(422);

    Mail::assertNothingQueued();
});
