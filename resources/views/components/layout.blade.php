@props(['title' => null])
    <!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-cream text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ? ($title.' — ') : '' }}{{ config('app.name', 'App') }}</title>
        @vite(['resources/css/app.css','resources/js/app.js'])
    </head>
    <body class="min-h-screen flex flex-col">
        
        <x-header/>

        <main class="flex-1 mt-20">
            {{ $slot }}
        </main>

        <x-footer/>

    </body>
</html>