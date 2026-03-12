{{-- @props(['state' => 'idle'])

@php
    $buttonClasses = 'relative justify-center duration-300 ease-in-out transition-all rounded bg-blue-600 hover:shadow-md/20 active:shadow-sm/20 px-3 py-2 text-sm text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:gray-gray-600 flex items-center gap-2';
    $spinnerClasses = 'absolute inset-0 grid place-items-center pointer-events-none animate-spin h-5 w-5 mr-0 absolute overflow-hidden border-2 border-white/25 rounded-full border-t-white';
    $isLoading = $state === 'loading';
@endphp

<button {{ $attributes->merge(['class' => $buttonClasses]) }} data-state="{{ $state }}" class="">
    <div data-spinner class="{{ $spinnerClasses }} {{ $isLoading ? '' : 'opacity-0' }}" aria-hidden="true"></div>
    <span data-label>{{ $isLoading ? 'Carregando...' : $slot }}</span>
</button> --}}

@props(['state' => 'idle'])

@php
    $buttonClasses = 'relative inline-flex items-center justify-center gap-2 px-3 py-2 text-sm rounded bg-blue-600
text-white transition-all duration-300 ease-in-out hover:shadow-md/20 active:shadow-sm/20 focus-visible:outline-2
focus-visible:outline-offset-2';
    $spinnerContainerClasses = 'absolute inset-0 grid place-items-center pointer-events-none transition-opacity';
    $spinnerDotClasses = 'h-5 w-5 animate-spin overflow-hidden border-2 border-white/25 rounded-full border-t-white';
    $isLoading = $state === 'loading';
    @endphp

<button {{ $attributes->merge(['class' => $buttonClasses]) }} data-state="{{ $state }}">
    <span data-label class="transition-opacity whitespace-nowrap {{ $isLoading ? 'opacity-0' : 'opacity-100' }}">
        {{ $slot }}
    </span>

    <span data-spinner class="{{ $spinnerContainerClasses }} {{ $isLoading ? 'opacity-100' : 'opacity-0' }}" aria-hidden="true">
        <span class="{{ $spinnerDotClasses }}"></span>
    </span>
</button>
