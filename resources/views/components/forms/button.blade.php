@props(['state' => 'idle'])

@php
    $buttonClasses = 'duration-300 ease-in-out transition-all rounded bg-blue-600 hover:shadow-md/20 active:shadow-sm/20 px-3 py-2 text-sm text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:gray-gray-600 flex items-center gap-2';
    $spinnerClasses = 'animate-spin h-5 w-5 mr-0 absolute overflow-hidden border-2 border-white/25 rounded-full border-t-white';
    $isLoading = $state === 'loading';
@endphp

<button {{ $attributes->merge(['class' => $buttonClasses]) }} data-state="{{ $state }}" class="">
    <div data-spinner class="{{ $spinnerClasses }} {{ $isLoading ? '' : 'opacity-0' }}" aria-hidden="true"></div>
    <span data-label>{{ $isLoading ? 'Carregando...' : $slot }}</span>
</button>
