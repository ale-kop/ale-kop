@props(['type' => 'text', 'button' => 'OK'])
@php
$divClasses = 'flex items-center rounded-md bg-white outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600'
@endphp
<div {{ $attributes->merge(['class' => $divClasses]) }}>
    <input type="{{ $type }}" {{ $attributes->class(['block min-w-0 grow py-1.5 pl-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm']) }} />
    <div class="shrink-0 p-2">
        <x-forms.button type="submit">{{$button}}</x-forms.button>
    </div>
</div>
