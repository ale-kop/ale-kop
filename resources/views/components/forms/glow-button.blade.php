@props([
    'href'  => null,
    'state' => 'idle',
    'size'  => 'md',   {{-- sm | md | lg --}}
    'dark'  => true,   {{-- true = fundo escuro | false = fundo branco --}}
])

@php
    $isLoading = $state === 'loading';

    $sizes = [
        'sm' => 'px-4 py-2 text-xs gap-1.5',
        'md' => 'px-6 py-3 text-sm gap-2',
        'lg' => 'px-8 py-4 text-base gap-2.5',
    ];

    $innerBg = $dark
        ? 'bg-gray-950 hover:bg-gray-900'
        : 'bg-white hover:bg-gray-50';

    $innerClasses = implode(' ', [
        'relative z-10 inline-flex items-center justify-center font-semibold text-white',
        'rounded-[10px] transition-all duration-200',
        '-translate-y-0 hover:-translate-y-px active:translate-y-0',
        $sizes[$size] ?? $sizes['md'],
        $innerBg,
    ]);

    $tag = $href ? 'a' : 'button';
@endphp

{{-- wrapper: animated conic-gradient border --}}
<div class="glow-btn-wrapper inline-block">

    {{-- soft bloom behind --}}
    <div class="glow-btn-bloom" aria-hidden="true"></div>

    {{-- 2px animated border --}}
    <div class="glow-btn-ring p-[2px] rounded-xl inline-block">

        @if ($href)
            <a href="{{ $href }}" {{ $attributes->merge(['class' => $innerClasses]) }}>
                <span class="{{ $isLoading ? 'opacity-0' : '' }}">{{ $slot }}</span>
                @if ($isLoading)
                    <span class="absolute inset-0 grid place-items-center" aria-hidden="true">
                        <span class="h-4 w-4 animate-spin rounded-full border-2 border-white/25 border-t-white"></span>
                    </span>
                @endif
            </a>
        @else
            <button {{ $attributes->merge(['class' => $innerClasses]) }} data-state="{{ $state }}">
                <span data-label class="{{ $isLoading ? 'opacity-0' : '' }}">{{ $slot }}</span>
                <span data-spinner class="absolute inset-0 grid place-items-center {{ $isLoading ? '' : 'opacity-0' }}" aria-hidden="true">
                    <span class="h-4 w-4 animate-spin rounded-full border-2 border-white/25 border-t-white"></span>
                </span>
            </button>
        @endif

    </div>
</div>
