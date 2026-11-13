{{-- Balão de conversa da página de oferta. from="me" (verde, à direita) | "them" (branco, à esquerda) --}}
@props(['from' => 'me', 'time' => null, 'read' => false])
<div {{ $attributes->class(['bubble', $from === 'me' ? 'bubble-me' : 'bubble-them']) }}>
    {{ $slot }}
    @if($time)
        <span class="bubble-meta">
            {{ $time }}
            @if($from === 'me')
                <svg class="{{ $read ? 'bubble-tick' : '' }}" width="16" height="11" viewBox="0 0 16 11" fill="none" aria-label="{{ $read ? 'Lida' : 'Enviada' }}"><path d="M1 6.2 3.8 9 9.8 2M6.6 8.4 7.2 9l6-7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            @endif
        </span>
    @endif
</div>
