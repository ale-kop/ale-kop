<!doctype html>
<html lang="pt-BR" class="scroll-smooth">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="{{ $description ?? config('custom.meta.description') }}">

        <meta property="og:title" content="{{ $title ?? config('custom.meta.title') }} - proComercial">
        <meta property="og:description" content="{{ $description ?? config('custom.meta.description') }}">
        <meta property="og:image" content="{{ $image ?? asset('img/procomercial-logo-lg.png') }}">
        <meta property="og:url" content="{{ URL::current() }}">

        <meta name="twitter:card" content="summary">
        <meta name="twitter:site" content="@proComercial">
        <meta name="twitter:creator" content="@proComercial">
        <meta property="twitter:title" content="{{ $title ?? config('custom.meta.title') }} - proComercial">
        <meta property="twitter:description" content="{{ $description ?? config('custom.meta.description') }}">
        <meta property="twitter:image" content="{{ $image ?? config('custom.meta.image') }}">
        <meta property="twitter:url" content="{{ URL::current() }}">

        <title>{{ isset($title) ? $title . ' - proComercial' : config('custom.meta.title') }}</title>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}" type="text/css">
        <script async
                src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5350362294453596"
                crossorigin="anonymous"></script>
        @stack('styles')
    </head>

    <x-mobile-menu></x-mobile-menu>

    <body class="bg-white text-gray-700 dark:bg-slate-900/95 dark:text-gray-200 font-inter-var">
        {{ $slot }}
    </body>

    <dialog class="w-[90%] max-w-5xl bg-transparent shadow rounded mx-auto p-0 animate-fade-in fixed" id="main-modal">
        <div data-content></div>
        <div data-loading class="p-5">
            <img src="{{ asset('img/carregando-modal.png') }}" class="w-1/4 mx-auto animate-spin-slow" alt="">
            <div class="py-3 text-center font-medium text-xl text-white">Carregando...</div>
        </div>
    </dialog>

    <script src="{{ mix('js/app.js') }}"></script>

    <x-alert></x-alert>
    @stack('scripts')

</html>
