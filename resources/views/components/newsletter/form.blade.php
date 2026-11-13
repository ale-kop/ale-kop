{{--
    Inscrição na newsletter em duas etapas: e-mail → código de 6 dígitos enviado ao e-mail.
    Comportamento (envio, troca de etapa, mensagens e confete) no módulo JS "newsletter".
    A mensagem de retorno aparece abaixo do campo e do botão.

    $id:       prefixo único por formulário na página.
    $form:     de qual formulário veio o cadastro (ex.: post-end, post-popup). Vai para
               newsletter_subscribers.source_form, junto com a página (source_path).
    $withName: mostra o campo "Nome" acima do e-mail.
--}}
@props(['id', 'form', 'withName' => false])

<div @class(['nl', 'nl--stacked' => $withName])
     data-newsletter
     data-subscribe-url="{{ route('newsletter.subscribe') }}"
     data-verify-url="{{ route('newsletter.verify') }}">

    <form id="form-{{ $id }}" class="nl-form">
        <input type="hidden" name="list" value="newsletter">
        <input type="hidden" name="source_url" value="{{ url()->current() }}">
        <input type="hidden" name="source_form" value="{{ $form }}">
        {{-- Honeypot: bots preenchem, pessoas deixam vazio --}}
        <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

        @if($withName)
            <label for="{{ $id }}-name" class="sr-only">Seu nome</label>
            <input id="{{ $id }}-name" type="text" name="name" placeholder="Seu nome" autocomplete="given-name" maxlength="100">
        @endif

        <div class="nl-row">
            <label for="{{ $id }}-email" class="sr-only">Seu e-mail</label>
            <input id="{{ $id }}-email" type="email" name="email" placeholder="seu@email.com" required autocomplete="email" data-nl-email>

            <label for="{{ $id }}-code" class="sr-only" hidden data-nl-code-label>Código de 6 dígitos</label>
            <input id="{{ $id }}-code" type="text" name="code" placeholder="000000" inputmode="numeric"
                   autocomplete="one-time-code" maxlength="6" pattern="[0-9]{6}" required disabled hidden data-nl-code>

            <x-forms.button type="submit" class="nl-btn" data-nl-submit>Assinar</x-forms.button>
        </div>
    </form>

    {{-- Mensagem abaixo do campo e do botão. O tom (info | success | error) escolhe o ícone e as cores. --}}
    <div class="nl-feedback" data-nl-feedback data-tone="info" role="status" aria-live="polite" hidden>
        <x-heroicon-s-check-circle class="nl-icon" data-icon="success" />
        <x-heroicon-s-envelope class="nl-icon" data-icon="info" />
        <x-heroicon-s-exclamation-circle class="nl-icon" data-icon="error" />
        <p><strong data-nl-title></strong><span data-nl-text></span></p>
    </div>

    <p class="nl-actions" data-nl-actions hidden>
        <button type="button" data-nl-resend>Reenviar código</button>
        <button type="button" data-nl-change>Usar outro e-mail</button>
    </p>
</div>
