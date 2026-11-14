{{--
    Página de oferta — Consultoria AKOP.
    Fonte da copy: doc "Estratégia AKOP" › Ofertas e funil › Página de oferta.
    Sem preço fixo nem checkout: termina no formulário de triagem (offers.consulting.send).
    Estilos: resources/css/components/offer.css (bloco "Consultoria").
    Trechos marcados com PLACEHOLDER precisam ser confirmados antes de publicar.
--}}
@php
    $symptoms = [
        'O pedido chega no WhatsApp e alguém precisa redigitar em outro lugar.',
        'A planilha funciona até a pessoa que entende dela sair de férias.',
        'O cliente pergunta algo que já foi respondido, e ninguém acha onde.',
        'A decisão foi tomada, mas ninguém lembra o porquê.',
    ];

    $transformations = [
        ['Uma mensagem', 'vira atualização no site'],
        ['Uma foto', 'vira registro'],
        ['Uma reunião', 'vira proposta e tarefa'],
        ['Um documento perdido', 'vira resposta em segundos'],
    ];

    $steps = [
        ['Conte o seu caso', 'Preencha um formulário curto ou mande um áudio de 2 minutos no WhatsApp. Se eu puder ajudar, marcamos uma conversa de 30 minutos.', null],
        ['Diagnóstico', 'Mapeio onde a informação está, por onde ela passa e onde se perde. Você recebe o desenho da solução e o preço fechado.', 'A partir de R$ 2.500'],
        ['Implementação', 'Construo, testo com você e entrego funcionando.', 'Preço fechado no diagnóstico'],
        ['Acompanhamento, se fizer sentido', 'Ajustes, novas automações e suporte mensal.', null],
    ];

    $reasons = [
        'Mais de 20 anos integrando sistemas em empresas de todos os tamanhos (Digibee, SAP, filas, bancos de dados).',
        'Mais de 20 anos vendendo e escrevendo propostas: eu entendo o negócio antes de entender a tecnologia.',
        'Regra antes da IA: a automação faz só o que foi combinado, com confirmação e registro de tudo.',
        // PLACEHOLDER: linkar o case da TDG quando estiver publicado.
        'Casos reais, como o sistema da TDG Informática.',
    ];

    $faq = [
        ['Preciso trocar meus sistemas?', 'Não. Eu integro o que você já usa.'],
        ['E se eu não tiver sistema nenhum?', 'Melhor ainda: começamos do jeito certo, simples.'],
        ['É seguro?', 'Cada ação tem regra, confirmação e registro. Nada é publicado sem você aprovar.'],
        ['Quanto tempo leva?', 'O diagnóstico, uma a duas semanas. A implementação depende do escopo e vem com prazo fechado.'],
    ];
@endphp

<x-layout title="Consultoria"
          description="Conversas no WhatsApp, planilhas, pastas no Drive e o que está na cabeça das pessoas viram registro, processo e ação. Sem trocar os sistemas que você já usa."
          :decoration="false">

<div class="offer">

    {{-- ═══ HERO ═══════════════════════════════════════════════════════ --}}
    <section>
        <x-container class="grid items-center gap-x-16 gap-y-14 pb-20 pt-6 lg:grid-cols-[1fr_440px] lg:pb-28">
            <div class="max-w-2xl">
                <p class="font-sans text-base font-semibold text-brand-deep">Consultoria AKOP</p>
                <h1 class="mt-3 font-sans text-[2.6rem]/[1.05] font-bold tracking-[-0.04em] text-pretty text-[var(--ink)] sm:text-6xl/[1.02]">
                    O que sua empresa sabe está espalhado. Eu faço isso trabalhar por você.
                </h1>
                <p class="mt-7 font-serif text-xl/8 text-gray-600 sm:text-[1.375rem]/9">
                    Conversas no WhatsApp, planilhas, pastas no Drive e o que está na cabeça das pessoas viram registro,
                    processo e ação. Sem trocar os sistemas que você já usa.
                </p>
                <div class="mt-10 flex flex-wrap items-center gap-x-6 gap-y-4">
                    <a href="#caso"
                       class="inline-flex items-center rounded-full bg-brand-deep px-7 py-3.5 font-sans text-base font-semibold text-white shadow-md shadow-brand-deep/20 transition-colors hover:bg-[#0F3A57] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">
                        Quero algo assim no meu negócio
                    </a>
                    <a href="#como-funciona" class="font-sans text-base font-semibold text-gray-700 underline decoration-gray-300 underline-offset-4 hover:decoration-gray-700">
                        Ver como funciona
                    </a>
                </div>
            </div>

            {{-- Espalhado → trabalhando: os fragmentos de cima viram as linhas de baixo (anima uma vez no load) --}}
            <div class="spread" role="img"
                 aria-label="Uma mensagem no WhatsApp, uma planilha, uma pasta antiga no Drive e um lembrete em papel viram três itens resolvidos: orçamento reenviado, pedido registrado e prazo anotado.">
                <div class="spread-scatter" aria-hidden="true">
                    <div class="frag frag-chat" style="--r: -3deg">
                        <span class="block font-sans text-[13px] font-semibold text-[#1F7A5C]">Grupo Sul</span>
                        Consegue me mandar de novo aquele orçamento de março?
                        <span class="frag-meta">18:42</span>
                    </div>
                    <div class="frag frag-file" style="--r: 2.5deg">
                        <span class="frag-icon bg-[#1D6F42]"><x-heroicon-s-table-cells class="size-4"/></span>
                        <span>pedidos_FINAL_v3.xlsx</span>
                    </div>
                    <div class="frag frag-file" style="--r: -1.5deg">
                        <span class="frag-icon bg-[#4087C2]"><x-heroicon-s-folder class="size-4"/></span>
                        <span>Clientes / antigos</span>
                    </div>
                    <div class="frag frag-note" style="--r: 3deg">
                        Prazo do Grupo Sul? Perguntar pra Cris
                    </div>
                </div>

                <div class="spread-arrow" aria-hidden="true">
                    <svg width="24" height="40" viewBox="0 0 24 40" fill="none"><path d="M12 2v34m0 0-7-7m7 7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>

                <ol class="spread-done spread-play" aria-hidden="true">
                    <li style="--at: .5s">
                        <span class="spread-kind">Ação</span>
                        <span>Orçamento de março reenviado ao Grupo Sul</span>
                        <x-heroicon-s-check-circle class="size-5 flex-none text-done"/>
                    </li>
                    <li style="--at: 1.1s">
                        <span class="spread-kind">Registro</span>
                        <span>Pedido nº 318 lançado na planilha de pedidos</span>
                        <x-heroicon-s-check-circle class="size-5 flex-none text-done"/>
                    </li>
                    <li style="--at: 1.7s">
                        <span class="spread-kind">Processo</span>
                        <span>Prazo do Grupo Sul: 12/12, combinado com a Cris em 03/11</span>
                        <x-heroicon-s-check-circle class="size-5 flex-none text-done"/>
                    </li>
                </ol>
            </div>
        </x-container>
    </section>

    {{-- ═══ O PROBLEMA ═════════════════════════════════════════════════ --}}
    <section class="bg-[var(--tint)] py-20 sm:py-28">
        <x-container class="grid gap-x-16 gap-y-10 lg:grid-cols-[minmax(0,26rem)_1fr]">
            <h2 class="font-sans text-3xl font-bold tracking-[-0.03em] text-pretty text-[var(--ink)] sm:text-[2.75rem]/[1.1]">
                Sua empresa já tem a informação. Ela só não está onde precisa estar.
            </h2>
            <div>
                <ul class="divide-y divide-gray-300/70 border-y border-gray-300/70">
                    @foreach($symptoms as $symptom)
                        <li class="py-5 font-serif text-xl/8 text-gray-700">{{ $symptom }}</li>
                    @endforeach
                </ul>
                <p class="mt-8 font-sans text-xl font-semibold text-[var(--ink)]">
                    Cada uma dessas é dinheiro e tempo saindo pela porta.
                </p>
            </div>
        </x-container>
    </section>

    {{-- ═══ O QUE EU FAÇO ══════════════════════════════════════════════ --}}
    <section class="py-20 sm:py-28">
        <x-container>
            <div class="max-w-2xl">
                <h2 class="font-sans text-3xl font-bold tracking-[-0.03em] text-[var(--ink)] sm:text-[2.75rem]/[1.1]">O que eu faço</h2>
                <p class="mt-5 font-serif text-xl/8 text-gray-600">Pego o que já existe no seu negócio e ligo ao resto.</p>
            </div>

            <dl class="mt-12 border-t border-gray-200">
                @foreach($transformations as [$from, $to])
                    <div class="grid gap-x-10 gap-y-1 border-b border-gray-200 py-6 sm:grid-cols-[minmax(0,18rem)_1fr] sm:items-baseline">
                        <dt class="font-sans text-2xl font-medium tracking-[-0.02em] text-gray-400 sm:text-3xl">{{ $from }}</dt>
                        <dd class="font-sans text-2xl font-bold tracking-[-0.03em] text-[var(--ink)] sm:text-3xl">{{ $to }}</dd>
                    </div>
                @endforeach
            </dl>

            <p class="mt-10 font-serif text-xl/8 text-gray-600">Você viu nos vídeos. Aqui é o mesmo, feito para o seu caso.</p>
        </x-container>
    </section>

    {{-- ═══ COMO FUNCIONA ══════════════════════════════════════════════ --}}
    <section id="como-funciona" class="scroll-mt-28 bg-[var(--tint)] py-20 sm:py-28">
        <x-container>
            <h2 class="font-sans text-3xl font-bold tracking-[-0.03em] text-[var(--ink)] sm:text-[2.75rem]/[1.1]">Como funciona</h2>

            <ol class="steps mt-12 grid gap-10 lg:grid-cols-4 lg:gap-8">
                @foreach($steps as $i => [$title, $text, $note])
                    <li class="steps-item">
                        <span class="steps-number">{{ $i + 1 }}</span>
                        <h3 class="mt-5 font-sans text-xl font-semibold tracking-[-0.01em] text-[var(--ink)]">{{ $title }}</h3>
                        <p class="mt-2 font-serif text-lg/7 text-gray-600">{{ $text }}</p>
                        @if($note)
                            <p class="mt-4 font-sans text-[15px] font-semibold text-brand-deep">{{ $note }}</p>
                        @endif
                    </li>
                @endforeach
            </ol>
        </x-container>
    </section>

    {{-- ═══ POR QUE COMIGO ═════════════════════════════════════════════ --}}
    <section class="py-20 sm:py-28">
        <x-container>
            <div class="grid items-center gap-x-16 gap-y-10 lg:grid-cols-[minmax(0,26rem)_1fr]">
                <img src="{{ asset('img/aleksandr-sitting-office-modern.png') }}"
                     alt="Aleksandr Kopelevitch sentado em seu escritório"
                     class="aspect-4/5 w-full rounded-3xl object-cover object-[60%_50%] shadow-xl shadow-black/10"
                     loading="lazy">
                <div class="max-w-2xl">
                    <h2 class="font-sans text-3xl font-bold tracking-[-0.03em] text-[var(--ink)] sm:text-[2.75rem]/[1.1]">Por que comigo</h2>
                    <ul class="mt-8 space-y-5">
                        @foreach($reasons as $reason)
                            <li class="flex gap-4 font-serif text-xl/8 text-gray-700">
                                <x-heroicon-s-check class="mt-1.5 size-5 flex-none text-done"/>
                                {{ $reason }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="mt-20">
                <h3 class="font-sans text-lg font-semibold text-gray-500">Empresas atendidas</h3>
                <div class="mt-6 flex flex-wrap items-center gap-x-12 gap-y-8">
                    @foreach([
                        ['logo-tdg', 'TDG Informática'],
                        ['logo-arysta', 'Arysta'],
                        ['logo-iac', 'Instituto Agronômico de Campinas'],
                        ['logo-apta', 'APTA SP'],
                        ['logo-lider-leiloes', 'Líder Leilões'],
                        ['logo-fundepag', 'Fundepag'],
                        ['logo-capital-consultoria', 'Capital Consultoria'],
                    ] as [$file, $name])
                        <img class="h-9 w-auto object-contain opacity-70 mix-blend-multiply grayscale sm:h-10" src="{{ asset("img/{$file}.webp") }}" alt="{{ $name }}" loading="lazy">
                    @endforeach
                </div>
            </div>
        </x-container>
    </section>

    {{-- ═══ PARA QUEM É / NÃO É ════════════════════════════════════════ --}}
    <section class="pb-20 sm:pb-28">
        <x-container class="grid gap-6 md:grid-cols-[3fr_2fr]">
            <div class="rounded-3xl bg-[var(--tint)] p-8 sm:p-10">
                <h2 class="flex items-center gap-3 font-sans text-2xl font-bold tracking-[-0.02em] text-[var(--ink)]">
                    <x-heroicon-s-check-circle class="size-7 flex-none text-done"/>
                    Para quem é
                </h2>
                <p class="mt-4 font-serif text-xl/8 text-gray-700">
                    Pequenas e médias empresas de serviço que vendem e operam pelo WhatsApp e sentem que a operação depende da memória de alguém.
                </p>
            </div>
            <div class="rounded-3xl p-8 ring-1 ring-gray-200 sm:p-10">
                <h2 class="flex items-center gap-3 font-sans text-2xl font-bold tracking-[-0.02em] text-[var(--ink)]">
                    <x-heroicon-s-x-circle class="size-7 flex-none text-gray-400"/>
                    Para quem não é
                </h2>
                <p class="mt-4 font-serif text-xl/8 text-gray-700">
                    Quem quer um chatbot para responder clientes no lugar de pessoas.
                </p>
            </div>
        </x-container>
    </section>

    {{-- ═══ PERGUNTAS FREQUENTES ═══════════════════════════════════════ --}}
    <section class="pb-20 sm:pb-28">
        <x-container class="grid gap-x-16 gap-y-10 lg:grid-cols-[minmax(0,22rem)_1fr]">
            <h2 class="font-sans text-3xl font-bold tracking-[-0.03em] text-[var(--ink)] sm:text-[2.75rem]/[1.1]">Perguntas frequentes</h2>
            <div class="faq divide-y divide-gray-200 border-y border-gray-200">
                @foreach($faq as [$question, $answer])
                    <details class="group py-6">
                        <summary class="flex items-start justify-between gap-6 font-sans text-lg font-semibold text-[var(--ink)]">
                            {{ $question }}
                            <x-heroicon-o-plus class="faq-icon mt-1 size-5 flex-none text-gray-400"/>
                        </summary>
                        <p class="mt-3 max-w-2xl font-serif text-lg/8 text-gray-600">{{ $answer }}</p>
                    </details>
                @endforeach
            </div>
        </x-container>
    </section>

    {{-- ═══ CHAMADA FINAL + TRIAGEM ════════════════════════════════════ --}}
    <section id="caso" class="scroll-mt-28 px-4 pb-20 sm:px-6">
        <div class="mx-auto max-w-7xl overflow-hidden rounded-3xl bg-brand-deep">
            <div class="grid gap-12 px-6 py-14 sm:px-12 sm:py-20 lg:grid-cols-[1fr_32rem] lg:px-16">
                <div class="max-w-xl">
                    <h2 class="font-sans text-3xl font-bold tracking-[-0.03em] text-white sm:text-5xl/[1.05]">
                        Viu um vídeo e pensou “queria isso aqui”? Me conta o seu caso.
                    </h2>
                    {{-- PLACEHOLDER: tempo de resposta prometido aqui, na confirmação e no ConsultingLeadController --}}
                    <p class="mt-6 font-serif text-xl/8 text-white/80">
                        Cinco perguntas, uns 5 minutos. Leio tudo e respondo pelo WhatsApp ou por e-mail.
                    </p>
                    <dl class="mt-10 space-y-4 border-t border-white/15 pt-8 font-sans text-white">
                        <div>
                            <dt class="text-sm text-white/60">Diagnóstico</dt>
                            <dd class="text-xl font-semibold">A partir de R$ 2.500</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-white/60">Projetos</dt>
                            <dd class="text-xl font-semibold">Preço fechado depois do diagnóstico</dd>
                        </div>
                    </dl>
                    @if($whatsappUrl)
                        <p class="mt-10 font-serif text-lg/7 text-white/80">
                            Prefere falar?
                            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="font-sans font-semibold text-white underline decoration-white/40 underline-offset-4 hover:decoration-white">
                                Mande um áudio de 2 minutos no WhatsApp</a>
                            respondendo às mesmas perguntas.
                        </p>
                    @endif
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-xl shadow-black/20 sm:p-8">
                    <form id="lead-form" class="space-y-5" data-lead-form>
                        @csrf
                        {{-- Honeypot --}}
                        <div class="hidden" aria-hidden="true">
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>
                        {{-- Trava de tempo: token com a hora em que o formulário foi gerado --}}
                        <input type="hidden" name="form_token" value="{{ $formToken }}">

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <x-forms.label for="lead-name">Nome</x-forms.label>
                                <x-forms.input id="lead-name" name="name" autocomplete="name" required/>
                            </div>
                            <div>
                                <x-forms.label for="lead-phone">WhatsApp com DDD</x-forms.label>
                                <x-forms.input id="lead-phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" placeholder="(19) 99999-9999" required/>
                            </div>
                        </div>
                        <div>
                            <x-forms.label for="lead-email">E-mail</x-forms.label>
                            <x-forms.input id="lead-email" name="email" type="email" autocomplete="email" required/>
                        </div>
                        <div>
                            <x-forms.label for="lead-business" class="!leading-6 pb-1.5">1. O que sua empresa faz e quantas pessoas trabalham nela?</x-forms.label>
                            <x-forms.input id="lead-business" name="business" required/>
                        </div>
                        <div>
                            <x-forms.label for="lead-problem" class="!leading-6 pb-1.5">2. Qual problema você quer resolver?</x-forms.label>
                            <x-forms.textarea id="lead-problem" name="problem" rows="3" required/>
                        </div>
                        <div>
                            <x-forms.label for="lead-process" class="!leading-6 pb-1.5">3. Como isso é feito hoje?</x-forms.label>
                            <x-forms.textarea id="lead-process" name="current_process" rows="3" required/>
                        </div>
                        <div>
                            <x-forms.label for="lead-cost" class="!leading-6 pb-1.5">4. Quanto esse problema custa por mês, em tempo ou dinheiro?</x-forms.label>
                            <x-forms.input id="lead-cost" name="monthly_cost" placeholder="Uma estimativa serve"/>
                        </div>
                        <div>
                            <x-forms.label for="lead-decision" class="!leading-6 pb-1.5">5. Quem decide sobre isso na empresa?</x-forms.label>
                            <x-forms.input id="lead-decision" name="decision_maker" required/>
                        </div>

                        <x-forms.button data-ak-ajax="lead-form"
                                        data-ak-action="{{ route('offers.consulting.send') }}"
                                        class="w-full !rounded-full !bg-brand-deep !py-3.5 !text-base !font-semibold hover:!bg-[#0F3A57]">
                            Contar o meu caso
                        </x-forms.button>
                    </form>

                    <div data-lead-done hidden class="py-10 text-center" role="status">
                        <x-heroicon-s-check-circle class="mx-auto size-12 text-done"/>
                        <p class="mt-5 font-sans text-2xl font-bold tracking-[-0.02em] text-[var(--ink)]">Recebi o seu caso</p>
                        <p class="mt-3 font-serif text-lg/7 text-gray-600">Respondo em até 2 dias úteis pelo WhatsApp ou por e-mail.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
</x-layout>
