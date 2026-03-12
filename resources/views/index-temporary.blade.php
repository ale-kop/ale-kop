<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-cream text-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ale Kop</title>
    <meta name="description" content="Conteúdo sobre IA, vendas consultivas, marketing e comunicação eficaz.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream">

{{-- ░░ Fundo ambiente ░░ --}}
<div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
    <div class="hero-grid absolute inset-0 animate-ambient-fade-in opacity-0"
         style="mask-image: radial-gradient(ellipse 80% 80% at 50% 40%, black 10%, transparent 70%);
                -webkit-mask-image: radial-gradient(ellipse 80% 80% at 50% 40%, black 10%, transparent 70%)"></div>
    <div class="absolute w-[600px] h-[600px] rounded-full opacity-0 animate-orb-1 -top-32 -right-32"
         style="background:radial-gradient(circle,rgba(43,76,126,0.22) 0%,transparent 65%);filter:blur(70px)"></div>
    <div class="absolute w-[500px] h-[500px] rounded-full opacity-0 animate-orb-2 -bottom-24 -left-24"
         style="background:radial-gradient(circle,rgba(108,45,127,0.18) 0%,transparent 65%);filter:blur(60px)"></div>
    <div class="absolute w-[350px] h-[350px] rounded-full opacity-0 animate-orb-3 top-1/2 right-0"
         style="background:radial-gradient(circle,rgba(74,124,89,0.14) 0%,transparent 70%);filter:blur(50px)"></div>
</div>

{{-- ░░ Conteúdo principal ░░ --}}
<main class="relative z-10 flex flex-col items-center justify-center min-h-screen px-6 py-16">

    <div class="w-full max-w-xl text-center">

        {{-- ── Foto ── --}}
        
    <img src="{{ asset('img/ale-kop-avatar-2.png') }}" alt="Ale Kop" class="w-1/2 rounded-full object-cover mx-auto mb-5 border border-sky-400/10 shadow-lg shadow-sky-100">
        

        {{-- ── Nome + badge ── --}}
        <div class="animate-fade-up" style="animation-delay:.2s">
            <h1 class="font-display text-4xl font-black text-gray-900 tracking-tight mb-2">Ale Kop</h1>
            <div class="inline-flex items-center gap-1.5 text-xs font-bold tracking-widest
                        uppercase text-accent bg-accent/10 border border-accent/20
                        px-3 py-1.5 rounded-full mb-5">
                ✦ IA · Vendas · Marketing · Comunicação
            </div>
        </div>

        {{-- ── Bio ── --}}
        <p class="text-base text-gray-600 leading-relaxed mb-6 animate-fade-up" style="animation-delay:.3s">
            Especialista em <strong class="text-gray-900">vendas consultivas e comunicação estratégica</strong>,
            ajudo profissionais a construir conversas que convencem — e a usar
            <strong class="text-gray-900">Inteligência Artificial</strong> para acelerar resultados em marketing,
            negociação e relacionamento com clientes.
        </p>

        {{-- ── Chips de interesse ── --}}
        <div class="flex flex-wrap justify-center gap-2 mb-10 animate-fade-up" style="animation-delay:.35s">
            @foreach([
                '🤖 Inteligência Artificial',
                '🤝 Vendas Consultivas',
                '📣 Marketing de Conteúdo',
                '🗣️ Comunicação Eficaz',
                '🧠 Persuasão',
                '💼 Negociação',
            ] as $topic)
                <span class="bg-white border border-stone-200 text-gray-600 text-xs font-medium
                             px-3 py-1.5 rounded-full shadow-sm">{{ $topic }}</span>
            @endforeach
        </div>

        {{-- ── Newsletter ── --}}
        <div class="bg-white border border-stone-200 rounded-2xl p-6 mb-8 animate-fade-up"
             style="animation-delay:.4s;box-shadow:0 8px 32px rgba(20,74,110,0.07),0 0 0 1px rgba(0,0,0,0.03)">

            <div class="text-xs font-black uppercase tracking-widest text-brand mb-1">Newsletter</div>
            <p class="text-sm text-gray-500 mb-5 leading-relaxed">
                Conteúdo sobre IA, vendas e comunicação direto no seu e-mail.<br>
                <span class="text-gray-400">Sem spam. Cancele quando quiser.</span>
            </p>

            {{-- SendPulse Simple Subscription Form --}}
            <form action="https://login.sendpulse.com/forms/simple/u/eyJ1c2VyX2lkIjo5MzYxOTEzLCJhZGRyZXNzX2Jvb2tfaWQiOjYwNjE5NCwibGFuZyI6InB0LWJyIn0="
                  method="post"
                  class="flex flex-col gap-3 text-left">

                <div>
                    <x-forms.label for="sp_name">Nome</x-forms.label>
                    <x-forms.input type="text" name="name" id="sp_name"
                                   placeholder="Seu nome" class="w-full" />
                </div>

                <div>
                    <x-forms.label for="sp_email">
                        E-mail <span class="text-accent font-bold">*</span>
                    </x-forms.label>
                    <x-forms.input type="email" name="email" id="sp_email"
                                   placeholder="seu@email.com" required class="w-full" />
                </div>

                <input type="hidden" name="sender" value="Y29udGF0b0BhbGVrb3AuY29t">

                <div class="flex justify-center mt-1">
                    <x-forms.glow-button size="md">
                        Quero receber os conteúdos →
                    </x-forms.glow-button>
                </div>

            </form>
            {{-- /SendPulse Simple Subscription Form --}}

        </div>

        {{-- ── Links sociais ── --}}
        <div class="flex items-center justify-center gap-3 animate-fade-up" style="animation-delay:.5s">

            <a href="https://x.com/alekop_com" target="_blank" rel="noopener noreferrer"
               class="flex items-center gap-2 bg-white border border-stone-200 rounded-xl
                      px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm
                      hover:border-gray-400 hover:text-gray-900 hover:shadow-md
                      transition-all duration-200">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
                @alekop_com
            </a>

            <a href="https://www.linkedin.com/in/alekop" target="_blank" rel="noopener noreferrer"
               class="flex items-center gap-2 bg-white border border-stone-200 rounded-xl
                      px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm
                      hover:border-[#0A66C2] hover:text-[#0A66C2] hover:shadow-md
                      transition-all duration-200">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
                LinkedIn
            </a>

        </div>

        {{-- ── Rodapé mínimo ── --}}
        <p class="text-xs text-gray-400 mt-10 animate-fade-up" style="animation-delay:.55s">
            © {{ date('Y') }} Ale Kop · Em breve, muito mais conteúdo aqui.
        </p>

    </div>

</main>

</body>
</html>
