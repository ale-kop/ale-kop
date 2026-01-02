@props(['type' => 'text', 'button' => 'OK'])
@php
$divClasses = 'flex items-center ring ring-gray-200 rounded-sm bg-white outline-none transition duration-150 focus-within:shadow-[0_0_7px_1px_rgba(100,175,255,0.45)]'
@endphp
<div {{ $attributes->merge(['class' => $divClasses]) }}>
    <input type="{{ $type }}" {{ $attributes->class(['block min-w-0 grow py-1.5 pl-3 text-sm md:text-base text-gray-900 placeholder:text-gray-400 focus:outline-none']) }} />
    <div class="shrink-0 p-2">
        <x-forms.button type="submit">{{$button}}</x-forms.button>
    </div>
</div>
