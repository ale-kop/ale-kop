{{--
    Formulário de inscrição na newsletter (fluxo AJAX de ajax-post.js via data-ak-ajax).
    $id: prefixo único por formulário na página.
    $modalId: se informado, o servidor devolve modalIdToClose e o diálogo fecha após o sucesso.
--}}
@props(['id', 'modalId' => null])

<form id="form-{{ $id }}" class="article-subscribe-form">
    <input type="hidden" name="list" value="newsletter">
    <input type="hidden" name="source_url" value="{{ url()->current() }}">
    @if($modalId)
        <input type="hidden" name="modal_id" value="{{ $modalId }}">
    @endif
    {{-- Honeypot: bots preenchem, pessoas deixam vazio --}}
    <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

    <label for="{{ $id }}-email" class="sr-only">Seu e-mail</label>
    <input id="{{ $id }}-email" type="email" name="email" placeholder="seu@email.com" required autocomplete="email">

    <x-forms.button class="article-btn article-btn-dark"
                    data-ak-ajax="form-{{ $id }}"
                    data-ak-action="{{ route('newsletter.subscribe') }}"
                    submit-button>
        Assinar
    </x-forms.button>
</form>
