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
                            <h2 class="text-3xl font-semibold tracking-tight font-serif text-white sm:text-3xl">
                                Sobre mim, Aleksandr Kopelevitch
                            </h2>
                            <p class="mt-6 text-base/7 text-white">
                                Há mais de 20 anos, atendo empresas e 
                                pessoas focando em planos de comunicação entre equipes, lideranças, campanhas de SEO, 
                                posicionamento e desenvolvimento de produtos.
                                <br><br>
                                Comunicar é a habilidade de deixar claro o que precisa ser entendido e feito.
                                <br><br>
                                Muitos <strong>pensam</strong> que comunicam bem. Mas a realidadde é diferente: falar e escrever pouco, de forma 
                                objetiva é raro tanto na vida profissional, quanto pessoal.
                                <br><br>
                                Felizmente, <strong>você pode treinar e aperfeiçoar essa habilidade!</strong>
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

    <style>
        @keyframes marquee-ltr {
            from { transform: translateX(-50%); }
            to   { transform: translateX(0%); }
        }
        .marquee-track {
            animation: marquee-ltr 28s linear infinite;
            will-change: transform;
        }
        .marquee-track:hover {
            animation-play-state: paused;
        }
    </style>

    <div class="bg-white py-12" id="clients">
        <div class="mx-auto max-w-7xl px-6 lg:px-8 mb-10">
            <h2 class="text-3xl font-serif font-bold tracking-tight text-gray-900">Clientes atendidos</h2>
        </div>

        <div class="relative overflow-hidden">
            {{-- Máscaras de fade nas bordas --}}
            <div class="pointer-events-none absolute inset-y-0 left-0 z-10 w-20 bg-gradient-to-r from-white to-transparent sm:w-32"></div>
            <div class="pointer-events-none absolute inset-y-0 right-0 z-10 w-20 bg-gradient-to-l from-white to-transparent sm:w-32"></div>

            <div class="marquee-track flex items-center">
                {{-- Conjunto 1 --}}
                <div class="flex shrink-0 items-center gap-12 px-6 sm:gap-16 sm:px-10">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-arysta.webp') }}" title="Arysta" alt="Arysta">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-iac.webp') }}" title="Instituto Agronômico de Campinas" alt="IAC">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-apta.webp') }}" title="APTA SP" alt="APTA SP">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-lider-leiloes.webp') }}" title="Líder Leilões" alt="Líder Leilões">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-fundepag.webp') }}" title="Fundepag" alt="Fundepag">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-capital-consultoria.webp') }}" title="Capital Consultoria" alt="Capital Consultoria">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-tdg.webp') }}" title="TDG Informática" alt="TDG">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-mossebo.webp') }}" title="Mossebo Interior Design" alt="Mossebo">
                </div>
                {{-- Conjunto 2 — duplicado para o loop contínuo --}}
                <div class="flex shrink-0 items-center gap-12 px-6 sm:gap-16 sm:px-10" aria-hidden="true">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-arysta.webp') }}" alt="">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-iac.webp') }}" alt="">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-apta.webp') }}" alt="">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-lider-leiloes.webp') }}" alt="">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-fundepag.webp') }}" alt="">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-capital-consultoria.webp') }}" alt="">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-tdg.webp') }}" alt="">
                    <img class="h-10 w-auto object-contain grayscale sm:h-12" src="{{ asset('img/logo-mossebo.webp') }}" alt="">
                </div>
            </div>
        </div>
    </div>

</x-layout>
