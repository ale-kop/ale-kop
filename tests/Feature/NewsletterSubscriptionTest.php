<?php

use App\Mail\NewsletterVerificationMail;
use App\Models\NewsletterList;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Models\Tag;
use App\Services\NewsletterVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
    NewsletterList::create(['name' => 'Newsletter', 'slug' => 'newsletter']);
});

/** Pede o código e devolve o código enviado por e-mail. */
function requestCode(array $extra = [], string $email = 'leitor@gmail.com'): string
{
    test()->postJson(route('newsletter.subscribe'), array_merge(['email' => $email], $extra))->assertOk();

    $code = null;
    Mail::assertQueued(NewsletterVerificationMail::class, function ($mail) use (&$code, $email) {
        $code = $mail->code;

        return $mail->hasTo($email);
    });

    return $code;
}

it('registers the e-mail as pending and sends a 6-digit code', function () {
    $this->postJson(route('newsletter.subscribe'), ['email' => 'Leitor@Gmail.com'])
        ->assertOk()
        ->assertJson(['type' => 'success', 'step' => 'verify', 'email' => 'leitor@gmail.com']);

    $subscriber = NewsletterSubscriber::firstOrFail();
    expect($subscriber->status)->toBe('pending')
        ->and($subscriber->lists)->toHaveCount(0)
        ->and($subscriber->verification_code)->not->toBeNull();

    Mail::assertQueued(NewsletterVerificationMail::class, fn ($mail) => preg_match('/^\d{6}$/', $mail->code) === 1);
});

it('stores only a hash of the code', function () {
    $code = requestCode();

    expect(NewsletterSubscriber::firstOrFail()->verification_code)->not->toBe($code)
        ->and(Hash::check($code, NewsletterSubscriber::firstOrFail()->verification_code))->toBeTrue();
});

it('confirms the subscription with the right code and joins the list', function () {
    $code = requestCode();

    $this->postJson(route('newsletter.verify'), ['email' => 'leitor@gmail.com', 'code' => $code])
        ->assertOk()
        ->assertJson(['type' => 'success', 'step' => 'done']);

    $subscriber = NewsletterSubscriber::firstOrFail();
    expect($subscriber->status)->toBe('active')
        ->and($subscriber->email_verified_at)->not->toBeNull()
        ->and($subscriber->verification_code)->toBeNull()
        ->and($subscriber->lists->pluck('slug')->all())->toBe(['newsletter']);
});

it('rejects a wrong code and keeps the subscriber pending', function () {
    $code = requestCode();
    $wrong = $code === '000000' ? '111111' : '000000';

    $this->postJson(route('newsletter.verify'), ['email' => 'leitor@gmail.com', 'code' => $wrong])
        ->assertStatus(422)
        ->assertJsonFragment(['type' => 'warning']);

    expect(NewsletterSubscriber::firstOrFail())->status->toBe('pending')->verification_attempts->toBe(1);
});

it('invalidates the code after too many wrong attempts', function () {
    $code = requestCode();
    $wrong = $code === '000000' ? '111111' : '000000';

    foreach (range(1, NewsletterVerificationService::MAX_ATTEMPTS) as $_) {
        $this->postJson(route('newsletter.verify'), ['email' => 'leitor@gmail.com', 'code' => $wrong])->assertStatus(422);
    }

    // nem o código certo vale mais
    $this->postJson(route('newsletter.verify'), ['email' => 'leitor@gmail.com', 'code' => $code])->assertStatus(422);
    expect(NewsletterSubscriber::firstOrFail()->status)->toBe('pending');
});

it('rejects an expired code', function () {
    $code = requestCode();
    $this->travel(NewsletterVerificationService::CODE_TTL_MINUTES + 1)->minutes();

    $this->postJson(route('newsletter.verify'), ['email' => 'leitor@gmail.com', 'code' => $code])->assertStatus(422);
    expect(NewsletterSubscriber::firstOrFail()->status)->toBe('pending');
});

it('answers the same way for an unknown e-mail', function () {
    $this->postJson(route('newsletter.verify'), ['email' => 'ninguem@gmail.com', 'code' => '123456'])
        ->assertStatus(422);
});

it('does not resend a code during the cooldown, then issues a new one that replaces the old', function () {
    $first = requestCode();

    $this->postJson(route('newsletter.subscribe'), ['email' => 'leitor@gmail.com'])
        ->assertStatus(429)
        ->assertJsonStructure(['message', 'retry_after']);

    $this->travel(NewsletterVerificationService::RESEND_COOLDOWN_SECONDS + 1)->seconds();
    Mail::fake(); // zera os envios para capturar só o segundo
    $second = requestCode();

    $wrongOld = $first === $second ? null : $first;
    if ($wrongOld) {
        $this->postJson(route('newsletter.verify'), ['email' => 'leitor@gmail.com', 'code' => $wrongOld])->assertStatus(422);
    }
    $this->postJson(route('newsletter.verify'), ['email' => 'leitor@gmail.com', 'code' => $second])->assertOk();
});

it('does not cut off an active subscriber who submits the form again', function () {
    $code = requestCode();
    $this->postJson(route('newsletter.verify'), ['email' => 'leitor@gmail.com', 'code' => $code])->assertOk();

    $this->travel(2)->minutes();
    requestCode();

    expect(NewsletterSubscriber::firstOrFail()->status)->toBe('active');
});

it('reactivates an unsubscribed person only after the code is confirmed', function () {
    NewsletterSubscriber::create(['email' => 'volta@gmail.com', 'status' => 'unsubscribed', 'unsubscribed_at' => now()]);

    $code = requestCode([], 'volta@gmail.com');
    expect(NewsletterSubscriber::firstOrFail()->status)->toBe('unsubscribed');

    $this->postJson(route('newsletter.verify'), ['email' => 'volta@gmail.com', 'code' => $code])->assertOk();
    expect(NewsletterSubscriber::firstOrFail())->status->toBe('active')->unsubscribed_at->toBeNull();
});

it('keeps pending subscribers out of campaign recipients', function () {
    requestCode();

    expect(NewsletterList::first()->activeSubscribers()->count())->toBe(0);
});

it('does not let anyone join an arbitrary list without the code', function () {
    NewsletterList::create(['name' => 'Outra', 'slug' => 'outra']);
    $code = requestCode(['list' => 'outra']);

    expect(NewsletterSubscriber::firstOrFail()->lists)->toHaveCount(0);

    $this->postJson(route('newsletter.verify'), ['email' => 'leitor@gmail.com', 'code' => $code, 'list' => 'outra'])->assertOk();
    expect(NewsletterSubscriber::firstOrFail()->lists->pluck('slug')->all())->toBe(['outra']);
});

// ── Origem do cadastro ──────────────────────────────────────

it('records the page and form the signup came from, resolving the post', function () {
    $post = Post::create(['name' => 'Follow-up', 'slug' => 'follow-up', 'content' => '<p>x</p>', 'tag_id' => Tag::create(['name' => 'Vendas', 'slug' => 'vendas'])->id]);

    requestCode(['source_url' => url('/follow-up/').'?utm_source=x#topo', 'source_form' => 'post-popup']);

    expect(NewsletterSubscriber::firstOrFail())
        ->source_path->toBe('/follow-up')
        ->source_form->toBe('post-popup')
        ->source_post_id->toBe($post->id);
});

it('records non-post pages by path, without a post id', function () {
    requestCode(['source_url' => url('/baixar'), 'source_form' => 'download']);

    expect(NewsletterSubscriber::firstOrFail())->source_path->toBe('/baixar')->source_post_id->toBeNull();
});

it('falls back to the Referer header and ignores other domains', function () {
    $this->postJson(route('newsletter.subscribe'), ['email' => 'a@gmail.com'], ['Referer' => url('/cursos')])->assertOk();
    $this->postJson(route('newsletter.subscribe'), ['email' => 'b@gmail.com', 'source_url' => 'https://outro-site.com/x'])->assertOk();

    expect(NewsletterSubscriber::where('email', 'a@gmail.com')->first()->source_path)->toBe('/cursos')
        ->and(NewsletterSubscriber::where('email', 'b@gmail.com')->first()->source_path)->toBeNull();
});

it('keeps the first source when the same person signs up again elsewhere', function () {
    requestCode(['source_url' => url('/primeiro-post')]);
    $this->travel(2)->minutes();
    requestCode(['source_url' => url('/segundo-post')]);

    expect(NewsletterSubscriber::firstOrFail()->source_path)->toBe('/primeiro-post');
});

it('rejects the honeypot and invalid e-mails', function () {
    $this->postJson(route('newsletter.subscribe'), ['email' => 'leitor@gmail.com', 'website' => 'http://spam'])->assertStatus(422);
    $this->postJson(route('newsletter.subscribe'), ['email' => 'isso-nao-e-email'])->assertStatus(422);

    expect(NewsletterSubscriber::count())->toBe(0);
});
