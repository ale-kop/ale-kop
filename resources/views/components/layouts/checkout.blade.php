{{-- Layout enxuto do checkout e da página do pedido: sem menu nem rodapé do site. --}}
@props(['title' => null])
<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title.' — ' : '' }}{{ config('app.name', 'App') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('img/favicon/favicon.ico') }}" sizes="any">
</head>
<body class="min-h-full bg-white font-sans text-[#1F2433] antialiased">
    {{ $slot }}
    <x-toast/>
</body>
</html>
