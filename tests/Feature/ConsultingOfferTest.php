<?php

use App\Mail\ConsultingLeadMail;
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

function leadPayload(array $extra = []): array
{
    return array_merge([
        'name' => 'Paula Ramos',
        'email' => 'Paula@Empresa.com.br',
        'phone' => '(19) 99876-5432',
        'business' => 'Manutenção de ar-condicionado, 12 pessoas',
        'problem' => 'Os pedidos chegam no WhatsApp e se perdem antes de virar ordem de serviço.',
        'current_process' => 'A Cris copia cada pedido numa planilha no fim do dia.',
        'monthly_cost' => 'Umas 20 horas',
        'decision_maker' => 'Eu e meu sócio',
    ], $extra);
}

function oldEnoughToken(): string
{
    $token = app(FormTimeTrap::class)->issue();
    test()->travel(FormTimeTrap::MIN_SECONDS + 1)->seconds();

    return $token;
}

it('renders the consulting page with the triage form', function () {
    $this->withoutVite()
        ->get(route('offers.consulting'))
        ->assertOk()
        ->assertSee('O que sua empresa sabe está espalhado.')
        ->assertSee('A partir de R$ 2.500')
        ->assertSee('name="form_token"', false)
        ->assertDontSee('wa.me');
});

it('offers the WhatsApp audio only when a number is configured', function () {
    config(['offers.consulting.whatsapp_number' => '+55 (19) 98888-7777']);

    $this->withoutVite()
        ->get(route('offers.consulting'))
        ->assertSee('https://wa.me/5519988887777?text=', false);
});

it('queues the triage to the contact address with reply-to set to the lead', function () {
    config(['mail.contact_address' => 'contato@alekop.com']);

    $this->postJson(route('offers.consulting.send'), leadPayload(['form_token' => oldEnoughToken()]))
        ->assertOk()
        ->assertJsonPath('type', 'success');

    Mail::assertQueued(ConsultingLeadMail::class, function (ConsultingLeadMail $mail) {
        return $mail->hasTo('contato@alekop.com')
            && $mail->hasReplyTo('paula@empresa.com.br')
            && $mail->lead['decision_maker'] === 'Eu e meu sócio'
            && ! array_key_exists('website', $mail->lead);
    });
});

it('links the lead WhatsApp with the Brazil country code in the e-mail', function () {
    $html = (new ConsultingLeadMail(leadPayload(['email' => 'paula@empresa.com.br'])))->render();

    expect($html)->toContain('https://wa.me/5519998765432')
        ->and($html)->toContain('Os pedidos chegam no WhatsApp');
});

it('silently drops a triage sent too fast', function () {
    $token = app(FormTimeTrap::class)->issue();

    $this->postJson(route('offers.consulting.send'), leadPayload(['form_token' => $token]))
        ->assertOk()
        ->assertJsonPath('type', 'success');

    Mail::assertNothingQueued();
});

it('requires the triage answers and a valid phone', function () {
    $this->postJson(route('offers.consulting.send'), leadPayload([
        'form_token' => oldEnoughToken(),
        'problem' => '',
        'phone' => 'meu zap',
    ]))->assertStatus(422);

    Mail::assertNothingQueued();
});
