<x-layout>

    {{-- Hero estilo Radiant --}}
    <div class="relative -mt-30">
        <div
             class="absolute inset-x-2 top-2 bottom-0 rounded-4xl
                    bg-linear-115 from-accent-light from-28% via-brand-light via-70% to-[#b7cee5]
                    sm:bg-linear-145
                    ring-1 ring-black/5 ring-inset">
        </div>

        <div class="relative px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:max-w-7xl lg:px-10">
                <div class="pt-36 pb-24 sm:pt-40 sm:pb-32 md:pb-48">
                    <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] lg:gap-3">

                        {{-- Texto --}}
                        <div class="flex items-center">
                            <div class="mx-auto flex max-w-4xl flex-col gap-6 max-lg:text-center">
                                <h1
                                    class="font-serif text-5xl/[0.9] font-medium tracking-tight text-balance text-gray-950 sm:text-7xl/[0.8] md:text-7xl/[1]">
                                    Comunicar é garantir que a mensagem foi entendida
                                </h1>
                                <p class="text-xl/7 font-serif font-medium text-gray-950/75 sm:text-3xl/8">
                                    Estudo e compartilho o que aprendi em mais de 20 anos comunicando profissionalmente.
                                
                                </p>
                                <p class="text-base font-medium text-brand-deep/90">
                                    Vem junto para aprender a <strong class="text-accent">Apresentar, Instruir, Reunir, Vender</strong> e comunicar bem em qualquer situação

                                </p>

                                <div class="flex flex-col gap-x-6 gap-y-4 sm:flex-row max-lg:justify-center mt-2">
                                    {{-- <a href="{{ route('courses.index') }}"
                                       class="inline-flex items-center justify-center px-4 py-[calc(--spacing(2)-1px)] rounded-full border border-transparent bg-gray-950 shadow-md text-base font-medium text-white hover:bg-gray-800 transition-colors">
                                        Quero aprender o método
                                    </a> --}}
                                    <a href="#posts"
                                       class="relative inline-flex items-center justify-center px-4 py-[calc(--spacing(2)-1px)] rounded-full border border-transparent bg-white/15 shadow-md ring-1 ring-brand/15 after:absolute after:inset-0 after:rounded-full after:shadow-[inset_0_0_2px_1px_#ffffff4d] text-base font-medium text-gray-950 hover:bg-white/20 transition-colors">
                                        Ler posts
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Foto --}}
                        <div class="max-lg:mx-auto max-lg:max-w-xs max-lg:mt-16">
                            <div
                                 class="-m-2 rounded-4xl bg-white/15 shadow-[inset_0_0_2px_1px_#ffffff4d] ring-1 ring-black/5">
                                <div class="rounded-4xl p-2 shadow-md shadow-black/5">
                                    <div
                                         class="overflow-hidden rounded-3xl shadow-2xl outline outline-1 -outline-offset-1 outline-black/10">
                                        <img alt="Aleksandr Kopelevitch"
                                             class="aspect-6/7 object-cover object-[-50px]"
                                             src="{{ asset('img/aleksandr-sitting-office-modern.png') }}">
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 mt-4 text-right italic font-serif px-4">
                                Aleksandr Kopelevitch
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="posts" class="py-14 max-w-7xl mx-auto px-6 lg:px-8">

        @php
            $sidePosts = $gridPosts->take(3);
            $remainingPosts = $gridPosts->skip(3);
        @endphp

        @if ($featuredPost || $sidePosts->isNotEmpty())
            {{-- relative: ancora o absolute dos posts laterais; height = post em destaque --}}
            <div class="relative flex flex-col gap-6 mb-14 lg:block">

                {{-- Post em destaque: padding-right reserva espaço para os laterais no desktop --}}
                @if ($featuredPost)
                    <a href="{{ route('posts.show', $featuredPost->slug) }}"
                       class="group flex flex-col gap-4 lg:pr-[calc(30%+1.5rem)]">
                        <div
                             class="aspect-video rounded-xl overflow-hidden bg-stone-100 shadow border border-stone-300">
                            @if ($url = $featuredPost->image('large'))
                                <img src="{{ $url }}" alt="{{ $featuredPost->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-5xl bg-stone-200">📄
                                </div>
                            @endif
                        </div>
                        <div class="text-center">
                            <h2
                                class="font-serif text-2xl font-black text-gray-900 leading-tight group-hover:text-brand transition-colors mb-2">
                                {{ $featuredPost->name }}
                            </h2>
                            @if ($desc = data_get($featuredPost->meta, 'description'))
                                <p class="text-sm text-gray-500 leading-relaxed max-w-md mx-auto mb-3">
                                    {{ $desc }}</p>
                            @endif
                            <p class="text-xs text-gray-400 uppercase tracking-widest">
                                {{ $featuredPost->created_at->translatedFormat('d M, Y') }}
                                {{-- @if ($featuredPost->tag)
                                    &middot; {{ Str::upper($featuredPost->tag->name) }}
                                @endif --}}
                            </p>
                        </div>
                    </a>
                @endif

                {{-- Posts laterais: absolute no desktop → h-full = altura do post em destaque --}}
                @if ($sidePosts->isNotEmpty())
                    <div
                         class="flex flex-col divide-y divide-gray-100
                                lg:absolute lg:top-0 lg:right-0 lg:w-[30%] lg:h-full lg:overflow-hidden">
                        @foreach ($sidePosts as $post)
                            <a href="{{ route('posts.show', $post->slug) }}"
                               class="group flex items-start gap-4 py-5 first:pt-0 last:pb-0">
                                <div class="flex-1 min-w-0">
                                    <h3
                                        class="font-serif font-bold text-gray-900 leading-snug group-hover:text-brand transition-colors text-base lg:text-xl line-clamp-3">
                                        {{ $post->name }}
                                    </h3>
                                    <p class="text-xs text-gray-400 mt-1.5 uppercase tracking-widest">
                                        {{ $post->created_at->translatedFormat('M d, Y') }}
                                        {{-- @if ($post->tag)
                                            &middot; {{ Str::upper($post->tag->name) }}
                                        @endif --}}
                                    </p>
                                </div>
                                <div
                                     class="shrink-0 w-20 h-20 rounded-lg overflow-hidden bg-stone-100 shadow border border-stone-300">
                                    @if ($url = $post->image('thumb'))
                                        <img src="{{ $url }}" alt="{{ $post->name }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div
                                             class="w-full h-full flex items-center justify-center text-2xl bg-stone-200">
                                            📄</div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif

            </div>
        @endif

        @if ($remainingPosts->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-10">
                @foreach ($remainingPosts as $post)
                    <a href="{{ route('posts.show', $post->slug) }}" class="group flex flex-col gap-3">
                        <div
                             class="aspect-video rounded-lg overflow-hidden bg-stone-100 shadow border border-stone-300">
                            @if ($url = $post->image('medium'))
                                <img src="{{ $url }}" alt="{{ $post->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl">📄</div>
                            @endif
                        </div>
                        <div>
                            <h3
                                class="font-bold text-gray-900 leading-snug group-hover:text-brand transition-colors mb-1">
                                {{ $post->name }}
                            </h3>
                            @if ($desc = data_get($post->meta, 'description'))
                                <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">{{ $desc }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-2 uppercase tracking-wide">
                                {{ $post->created_at->translatedFormat('d M') }}
                                @if ($post->tag)
                                    &middot; {{ $post->tag->name }}
                                @endif
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

    </div>

    <div class="relative">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="absolute -top-10">
            <path fill="#fff" fill-opacity="1"
                  d="M0,256L80,234.7C160,213,320,171,480,165.3C640,160,800,192,960,208C1120,224,1280,224,1360,224L1440,224L1440,0L1360,0C1280,0,1120,0,960,0C800,0,640,0,480,0C320,0,160,0,80,0L0,0Z">
            </path>
        </svg>
        <div class="from-purple-300/30 to-white bg-gradient-to-b py-10 mt-10 z-0">

            <div class="relative">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div
                         class="mx-auto flex max-w-2xl flex-col gap-16 bg-black/60 px-6 py-16 ring-1 ring-white/10 sm:rounded-3xl sm:p-8 lg:mx-0 lg:max-w-none lg:flex-row lg:items-start lg:py-20 xl:gap-x-20 xl:px-20">
                        <img alt="Mesa com copo de café a imagens com stickers de PHP, JS e fotos pessoais"
                             class="h-96 w-full flex-none rounded-2xl object-cover shadow-xl lg:aspect-square lg:h-auto lg:max-w-sm"
                             src="{{ asset('img/avatar-akop-4.webp') }}">
                        <div class="w-full flex-auto">
                            <h2 class="text-3xl font-semibold tracking-wide text-white sm:text-4xl">
                                Olá 👋🏻 sou<br> Aleksandr Kopelevitch
                            </h2>
                            <p class="mt-6 text-lg/6 text-white font-serif">
                                Sou apaixonado por comunicação escrita e falada.<br>
                                Há 20 anos escrevo e analiso propostas comerciais, converso com clientes para entender suas necessidades e planejo soluções para seus problemas.
                                <br><br>
                                Trabalhei em empresas pequenas, médias e grandes para descobrir como funcionam as engrenagens corporativas e tomadas de decisão em vendas B2B.
                                <br><br>
                                Compartilho esse conhecimento com colegas, amigos e qualquer pessoa com interesse.
                                <br><br>
                                Percebi que existe muita vontade em aprender <strong>como comunicar melhor em vendas, posicionamento profissional e mesmo no dia a dia do corporativo</strong>.
                                
                                <br><br>Por isso iniciei o projeto Comunicação Eficaz.
                            </p>
                            {{-- <ul role="list"
                                class="mt-10 grid grid-cols-1 gap-x-8 gap-y-3 leading-7 text-gray-100 sm:grid-cols-2">
                                <li class="flex gap-x-3">
                                    <svg class="h-7 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd" />
                                    </svg>
                                    Modelo de Proposta Comercial
                                </li>
                                <li class="flex gap-x-3">
                                    <svg class="h-7 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd" />
                                    </svg>
                                    Modelos de Planilhas Excel
                                </li>
                                <li class="flex gap-x-3">
                                    <svg class="h-7 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd" />
                                    </svg>
                                    Modelos de Cartas de Apresentação
                                </li>
                                <li class="flex gap-x-3">
                                    <svg class="h-7 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd" />
                                    </svg>
                                    Modelos de apresentação PowerPoint
                                </li>
                                <li class="flex gap-x-3">
                                    <svg class="h-7 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd" />
                                    </svg>
                                    Funções e Fórmulas Excel
                                </li>
                                <li class="flex gap-x-3">
                                    <svg class="h-7 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd" />
                                    </svg>
                                    Modelos de Currículos
                                </li>
                            </ul> --}}
                            <div class="mt-10 flex group">
                                <a href="#clients"
                                   class="scroll-smooth text-sm font-semibold leading-6 text-indigo-100 hover:text-white">Veja
                                    alguns clientes
                                    <div aria-hidden="true"
                                         class="ml-2 group-hover:translate-y-1 rotate-90 duration-300 ease-in-out transition-all">
                                        &rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-x-0 -top-16 -z-10 flex transform-gpu justify-center overflow-hidden blur-3xl"
                     aria-hidden="true">
                    <div
                         class="aspect-[1318/752] w-[82.375rem] flex-none bg-gradient-to-r from-[#80caff] to-[#4f46e5] opacity-25"
                         style="clip-path: polygon(73.6% 51.7%, 91.7% 11.8%, 100% 46.4%, 97.4% 82.2%, 92.5% 84.9%, 75.7% 64%, 55.3% 47.5%, 46.5% 49.4%, 45% 62.9%, 50.3% 87.2%, 21.3% 64.1%, 0.1% 100%, 5.4% 51.1%, 21.4% 63.9%, 58.9% 0.2%, 73.6% 51.7%)">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-1 items-start gap-x-8 gap-y-16 lg:grid-cols-2 py-10">
                <div class="mx-auto w-full max-w-xl lg:mx-0">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900" id="clients">Clientes atendidos</h2>
                    <p class="mt-6 text-lg leading-8 text-gray-600">Ao longo dos mais de 20 anos de jornada, atendi
                        inúmeras empresas criando sites, softwares de gestão, campanhas de SEO e prestando consultoria
                        para os mais variados fins dentro do espectro de produtos Web.</p>
                    <div class="mt-8 flex group">
                        <a href="#projects"
                           class="active:scale-[0.99] transform transition-all duration-200 ease-in-out rounded-md bg-sky-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Conheça meus principais projetos</a>
                    </div>
                </div>
                <div
                     class="mx-auto grid w-full max-w-xl grid-cols-2 items-center gap-y-12 lg:mx-0 lg:max-w-none lg:pl-8">
                    <img class="max-h-16 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-arysta.webp') }}" title="Arysta" alt="Arysta - Logo">
                    <img class="max-h-16 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-iac.webp') }}" title="Instituto Agronômico de Campinas"
                         alt="Instituo Agronômico de Campinas - Logo">
                    <img class="max-h-20 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-apta.webp') }}" title="APTA SP" alt="APTA Governo SP - Logo">
                    <img class="max-h-16 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-lider-leiloes.webp') }}" title="Líder Leilões"
                         alt="Líder Leilões - Logo">
                    <img class="max-h-16 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-fundepag.webp') }}" title="Fundepag" alt="Fundepag - Logo">
                    <img class="max-h-16 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-capital-consultoria.webp') }}" title="Capital Consultoria"
                         alt="Capital Consultoria - Logo">
                    <img class="max-h-20 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-tdg.webp') }}" title="TDG Informática" alt="TDG - Logo">
                    <img class="max-h-16 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-mossebo.webp') }}" title="Mossebo Interior Design"
                         alt="Mossebo Interior Design - Logo">
                </div>
            </div>
        </div>
    </div>


    {{-- App Showcase --}}
    <div class="overflow-hidden bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div
                 class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">
                <div class="lg:pr-8 lg:pt-4">
                    <div class="lg:max-w-lg">
                        <h2 class="text-base font-semibold leading-7 text-sky-600" id="projects">Software de Gestão
                        </h2>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">SGP AgroSP</p>
                        <p class="mt-6 text-lg leading-8 text-gray-600">O SGP AgroSP integra as área da pesquisa com
                            outras coordenadorias da Secretaria de Agricultura e Abastecimento, além de promover a
                            interação entre seus institutos na programação de PD&I</p>
                        <dl class="mt-10 max-w-xl space-y-8 text-base leading-7 text-gray-600 lg:max-w-none">
                            <div class="relative pl-9">
                                <dt class="inline font-semibold text-gray-900">
                                    <svg class="absolute left-1 top-1 h-5 w-5 text-sky-600" viewBox="0 0 20 20"
                                         fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M5.5 17a4.5 4.5 0 01-1.44-8.765 4.5 4.5 0 018.302-3.046 3.5 3.5 0 014.504 4.272A4 4 0 0115 17H5.5zm3.75-2.75a.75.75 0 001.5 0V9.66l1.95 2.1a.75.75 0 101.1-1.02l-3.25-3.5a.75.75 0 00-1.1 0l-3.25 3.5a.75.75 0 101.1 1.02l1.95-2.1v4.59z"
                                              clip-rule="evenodd" />
                                    </svg>
                                    Disponível 24/7
                                </dt>
                                <dd class="inline">Software disponível o tempo todo: do computador de mesa, notebook,
                                    tablet ou smartphone. Basta ter uma conexão com a Internet para usá-lo a qualquer
                                    hora e em qualquer lugar.
                                </dd>
                            </div>
                            <div class="relative pl-9">
                                <dt class="inline font-semibold text-gray-900">
                                    <svg class="absolute left-1 top-1 h-5 w-5 text-sky-600" viewBox="0 0 20 20"
                                         fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                                              clip-rule="evenodd" />
                                    </svg>
                                    Dados em segurança
                                </dt>
                                <dd class="inline">Além de contar com transmissão de dados através do protocolo HTTPS,
                                    está em conformidade com a LGPD, mantendo as informações encriptadas no banco de
                                    dados.
                                </dd>
                            </div>
                            <div class="relative pl-9">
                                <dt class="inline font-semibold text-gray-900">
                                    <svg class="absolute left-1 top-1 h-5 w-5 text-sky-600"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                              d="M10 1a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 1zM5.05 3.05a.75.75 0 011.06 0l1.062 1.06A.75.75 0 116.11 5.173L5.05 4.11a.75.75 0 010-1.06zm9.9 0a.75.75 0 010 1.06l-1.06 1.062a.75.75 0 01-1.062-1.061l1.061-1.06a.75.75 0 011.06 0zM3 8a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5A.75.75 0 013 8zm11 0a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5A.75.75 0 0114 8zm-6.828 2.828a.75.75 0 010 1.061L6.11 12.95a.75.75 0 01-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zm3.594-3.317a.75.75 0 00-1.37.364l-.492 6.861a.75.75 0 001.204.65l1.043-.799.985 3.678a.75.75 0 001.45-.388l-.978-3.646 1.292.204a.75.75 0 00.74-1.16l-3.874-5.764z"
                                              clip-rule="evenodd" />
                                    </svg>
                                    Interface amigável
                                </dt>
                                <dd class="inline">Elaborado com as melhores práticas de User Experience, o SGP AgroSP
                                    possui interface amigável e intuitiva, para todos os usuários.
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
                <img src="{{ asset('img/macbook-sgp-procomercial.webp') }}" alt="Product screenshot"
                     class="w-[57rem] md:w-[40rem] rounded-xl shadow-xl ring-1 ring-gray-400/10 md:-ml-4 lg:-ml-0"
                     width="2432" height="1442">
            </div>
        </div>
    </div>

    <div class="text-center font-5xl text-gray-400 font-medium">Em breve, vou colocar todos os projetos aqui...</div>

    {{-- /App Showcase --}}

</x-layout>
