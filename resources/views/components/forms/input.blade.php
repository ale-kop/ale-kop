@props([
    'type' => 'text',
    'iconLeft' => null,
    'iconRight' => null,
])
<div class="flex items-center rounded-sm bg-white pl-3 outline-none ring ring-gray-200 transition duration-150 focus-within:shadow-[0_0_7px_1px_rgba(100,175,255,0.45)]">
    @if($iconLeft)
        <x-dynamic-component :component="$iconLeft" class="size-5 text-gray-400" />
    @endif
    <input type="{{ $type }}" {{ $attributes->class(['block min-w-0 grow py-1.5 px-2 md:text-base text-gray-900 placeholder:text-gray-400 focus:outline-none text-sm']) }} />
    @if($iconRight)
        <x-dynamic-component :component="$iconRight" class="size-5 p-0 bg-red-300 text-gray-400" />
    @endif
</div>
