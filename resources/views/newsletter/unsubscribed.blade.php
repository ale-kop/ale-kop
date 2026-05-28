<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Descadastrado · Ale Kop</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-cream flex items-center justify-center px-6 py-16">
<div class="w-full max-w-md text-center">
    <a href="{{ url('/') }}" class="inline-block font-display text-2xl font-black text-gray-900 mb-8">Ale Kop</a>

    <div class="bg-white border border-stone-200 rounded-2xl p-8 shadow-sm">
        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="text-xl font-bold text-gray-900 mb-2">Descadastro confirmado</h1>
        <p class="text-sm text-gray-500 mb-6">
            Você foi removido da nossa lista de e-mails.<br>
            Não enviaremos mais mensagens para
            <span class="font-medium text-gray-700">{{ $subscriber->email }}</span>.
        </p>

        <a href="{{ url('/') }}"
           class="inline-block bg-gray-900 hover:bg-gray-800 text-white font-semibold py-2.5 px-6 rounded-lg transition-colors text-sm">
            Voltar ao site
        </a>
    </div>
</div>
</body>
</html>
