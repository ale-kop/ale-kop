@props(['id' => 'file-upload', 'initialUrl' => null, 'clearField' => null])
@php
    $name = $attributes->get('name');
    $resolvedClearField = $clearField ?? ($name ? $name . '_remove' : null);
    $hasInitial = !empty($initialUrl);
@endphp
<div class="mt-2" data-file-upload @if($hasInitial) data-initial-url="{{ $initialUrl }}" @endif>
    @if($resolvedClearField)
        <input type="hidden" name="{{ $resolvedClearField }}" value="0" data-clear-flag />
    @endif
    <div data-placeholder class="flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10 @if($hasInitial) hidden @endif">
        <div class="text-center">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" class="mx-auto size-12 text-gray-300">
                <path d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clip-rule="evenodd" fill-rule="evenodd" />
            </svg>
            <div class="mt-4 flex text-sm text-gray-600">
                <label for="{{ $id }}" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                    <span>Upload a file</span>
                    <input id="{{ $id }}" type="file" {{ $attributes->except('class') }} class="sr-only" data-input />
                </label>
                <p class="pl-1">or drag and drop</p>
            </div>
            <p class="text-xs text-gray-600">PNG, JPG, GIF up to 10MB</p>
        </div>
    </div>

    <div data-preview class="relative w-fit @if(!$hasInitial) hidden @endif">
        <img data-preview-img alt="Preview da imagem" class="block max-h-64 w-auto rounded-lg object-contain border border-gray-200" @if($hasInitial) src="{{ $initialUrl }}" @endif />
        <button type="button" data-clear aria-label="Remover imagem"
                class="absolute -top-2 -right-2 inline-flex items-center justify-center size-8 rounded-full bg-black/60 text-white shadow hover:bg-white/95 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black/60 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                <path fill-rule="evenodd" d="M6.225 4.811a.75.75 0 0 1 1.06 0L12 9.525l4.716-4.714a.75.75 0 1 1 1.06 1.06L13.06 10.586l4.715 4.716a.75.75 0 1 1-1.06 1.06L12 11.646l-4.716 4.716a.75.75 0 1 1-1.06-1.06l4.714-4.716-4.714-4.714a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</div>
