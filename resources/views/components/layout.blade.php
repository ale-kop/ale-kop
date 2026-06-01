@props(['title' => null])
<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-white scroll-smooth">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ? ($title.' — ') : '' }}{{ config('app.name', 'App') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @vite(['resources/css/app.css','resources/js/app.js'])
    </head>
    <body class="min-h-screen flex flex-col bg-white text-gray-950 antialiased">

        <x-header/>

        <main class="relative flex-1 overflow-hidden">
            {{-- AKOP gradient blob (top-right decoration) --}}
            <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 top-0 mx-auto max-w-7xl">
                <div class="z-1 absolute -top-44 -right-60 h-60 w-2xl transform-gpu md:right-0
                            bg-linear-115 from-accent-light from-28% via-brand-light via-70% to-brand
                            rotate-[-10deg] rounded-full blur-3xl opacity-70"></div>
            </div>

            <div class="relative mt-30">
                {{ $slot }}
            </div>
        </main>

        <x-footer/>
        <x-toast/>
        <x-dialogs/>

    </body>
</html>