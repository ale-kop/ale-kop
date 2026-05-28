<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Descadastro · Ale Kop</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-cream flex items-center justify-center px-6 py-16">
<div class="w-full max-w-md text-center">
    <a href="{{ url('/') }}" class="inline-block font-display text-2xl font-black text-gray-900 mb-8">Ale Kop</a>

    <div class="bg-white border border-stone-200 rounded-2xl p-8 shadow-sm">
        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>

        <h1 class="text-xl font-bold text-gray-900 mb-2">Descadastrar da newsletter</h1>
        <p class="text-sm text-gray-500 mb-6">
            Tem certeza que deseja sair da newsletter?<br>
            @if($subscriber->name)
                <span class="font-medium text-gray-700">{{ $subscriber->name }}</span> ·
            @endif
            <span class="font-medium text-gray-700">{{ $subscriber->email }}</span>
        </p>

        <form action="{{ route('newsletter.unsubscribe.confirm', ['token' => $token]) }}" method="POST">
            @csrf
            <button type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 px-4 rounded-lg transition-colors text-sm">
                Confirmar descadastro
            </button>
        </form>

        <a href="{{ url('/') }}" class="block mt-4 text-sm text-gray-400 hover:text-gray-600 transition-colors">
            Voltar ao site
        </a>
    </div>
</div>
</body>
</html>
