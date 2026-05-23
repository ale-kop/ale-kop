<x-layout>
    {{-- ░░ Wrapper com fundo creme ░░ --}}
    <div class="-mt-30 bg-cream min-h-screen">

        {{-- ═══ HERO ═══════════════════════════════════════════════════════ --}}
        <div class="relative overflow-hidden">
            <div
                 class="w-full absolute top-0 h-[600px] z-1 opacity-60
                    bg-gradient-to-b from-white via-teal-50 to-transparent">
            </div>
            {{-- Grade quadriculada --}}
            <div class="hero-grid absolute inset-0 pointer-events-none z-2 animate-ambient-fade-in"></div>

            <section
                     class="relative z-10 max-w-[1100px] mx-auto px-6 pt-[calc(7.5rem+72px)] pb-16
                        grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                {{-- Texto esquerdo --}}
                <div>
                    <div class="inline-flex items-center gap-1.5 text-xs font-bold tracking-widest
                            uppercase text-accent bg-accent/10 border border-accent/20
                            px-3 py-1.5 rounded-full mb-5
                            animate-fade-up"
                         style="animation-delay:.1s">
                        ✦ Para profissionais de vendas, marketing e gestão
                    </div>

                    <h1 class="font-display text-4xl font-black leading-tight tracking-tight text-gray-900 mb-4
                           animate-fade-up"
                        style="animation-delay:.2s">
                        <em class="not-italic text-brand">Do primeiro <span class="mark-highlight">contato</span> ao
                            fechamento</em>
                    </h1>

                    <p class="text-base text-gray-500 leading-relaxed max-w-sm mb-8
                          animate-fade-up"
                       style="animation-delay:.3s">
                        Chega de curso de 40 horas que ninguém termina.<br>
                        O método Proposta Certa é direto e pode ser aplicado já.
                    </p>

                    {{-- CTA com glow animado --}}
                    <div class="mb-2 animate-fade-up" style="animation-delay:.4s">
                        <x-forms.glow-button href="{{ route('courses.index') }}" size="md">
                            Quero começar agora
                            <span class="text-orange-200">→</span>
                        </x-forms.glow-button>
                    </div>


                    {{-- Depoimento --}}
                    <div class="mt-7 flex items-start gap-3 bg-white border border-stone-100
                            rounded-xl p-3.5 max-w-sm shadow-sm
                            animate-fade-up"
                         style="animation-delay:.5s">
                        <span class="text-accent text-xl leading-none shrink-0 mt-0.5">"</span>
                        <p class="text-sm text-gray-500 leading-relaxed">
                            Mudou como eu conduzo reuniões de vendas. Em duas semanas fechei três contratos
                            que estavam parados.
                            <strong class="block text-gray-900 font-bold mt-1 text-xs">
                                — Rafael M., Consultor Comercial
                            </strong>
                        </p>
                    </div>

                    {{-- Prova social --}}
                    <div class="flex items-center gap-4 mt-3 pt-4 border-t border-stone-100
                            animate-fade-up"
                         style="animation-delay:.55s">
                        <div class="flex">
                            <div
                                 class="w-8 h-8 rounded-full border-2 border-cream bg-brand
                                    flex items-center justify-center text-xs font-extrabold text-white">
                                RM</div>
                            <div
                                 class="w-8 h-8 rounded-full border-2 border-cream bg-accent
                                    flex items-center justify-center text-xs font-extrabold text-white -ml-2">
                                CA</div>
                            <div
                                 class="w-8 h-8 rounded-full border-2 border-cream bg-done
                                    flex items-center justify-center text-xs font-extrabold text-white -ml-2">
                                JP</div>
                        </div>
                        <div>
                            <div class="text-amber-400 text-xs tracking-wide">★★★★★</div>
                            <p class="text-xs text-gray-500">
                                Mais de <strong class="text-gray-900">200 profissionais</strong>
                                já aplicaram o método
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card direito com orbs ambientes --}}
                <div class="relative flex items-center justify-center py-12
                        animate-fade-up"
                     style="animation-delay:.2s">

                    {{-- Orbs coloridos --}}
                    <div class="absolute w-80 h-80 rounded-full pointer-events-none opacity-0 animate-orb-1 -top-10 -right-2.5"
                         style="background:radial-gradient(circle,rgba(43,76,126,0.28) 0%,transparent 65%);filter:blur(45px)">
                    </div>
                    <div class="absolute w-60 h-60 rounded-full pointer-events-none opacity-0 animate-orb-2 bottom-2.5 -left-5"
                         style="background:radial-gradient(circle,rgba(108, 45, 127, 0.22) 0%,transparent 65%);filter:blur(38px)">
                    </div>
                    <div class="absolute w-44 h-44 rounded-full pointer-events-none opacity-0 animate-orb-3 top-[45%] -right-8"
                         style="background:radial-gradient(circle,rgba(74,124,89,0.18) 0%,transparent 70%);filter:blur(32px)">
                    </div>

                    {{-- Card flutuante --}}
                    <div class="relative z-10 w-full max-w-sm bg-white rounded-2xl overflow-visible"
                         style="box-shadow:0 24px 60px rgba(0,0,0,0.12),0 4px 16px rgba(0,0,0,0.06),0 0 0 1px rgba(0,0,0,0.04);animation:card-entry 0.8s cubic-bezier(0.22,1,0.36,1) both,card-float 8s ease-in-out 0.8s infinite">

                        {{-- Badge "ao vivo" --}}
                        <div
                             class="absolute -top-3.5 -right-6 z-20 bg-white border border-stone-100 rounded-full
                                px-3 py-1.5 text-xs font-bold text-gray-900 shadow-md
                                flex items-center gap-1.5 whitespace-nowrap animate-badge-float">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-badge-dot"></span>
                            3 alunos assistindo agora
                        </div>

                        <div
                             class="absolute -bottom-4 left-8 z-20 bg-white border border-stone-100 rounded-full
                                px-3 py-1.5 text-xs font-bold text-gray-900 shadow-md
                                flex items-center gap-1.5 whitespace-nowrap animate-badge-float">
                            <span class="w-2 h-2 rounded-full bg-orange-500 animate-badge-dot"
                                  style="animation-delay:.6s"></span>
                            Últimas vagas
                        </div>

                        <div class="rounded-2xl overflow-hidden">

                            {{-- Cabeçalho do card --}}
                            <div class="relative px-5 pt-5 pb-4 overflow-hidden bg-brand">
                                <div
                                     class="absolute -top-10 -right-10 w-36 h-36 rounded-full bg-white/5 pointer-events-none">
                                </div>
                                <div
                                     class="absolute -bottom-8 left-5 w-24 h-24 rounded-full bg-white/[0.03] pointer-events-none">
                                </div>
                                {{-- Shine line --}}
                                <div
                                     class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/35 to-transparent">
                                </div>

                                <div class="font-display text-xl font-bold text-white leading-snug mb-2.5 relative">
                                    <span class="text-orange-200 font-bold">Método:</span> Proposta Certa
                                </div>
                                <div class="flex gap-4 relative">
                                    <span class="text-xs text-white/70">⏱ 3h 20min</span>
                                    <span class="text-xs text-white/70">📋 5 módulos</span>
                                    <span class="text-xs text-white/70">★ Acesso 12 meses</span>
                                </div>
                            </div>

                            {{-- Lista de módulos --}}
                            @php
                                $demoModules = [
                                    ['🔍', 'Diagnóstico do cliente', '38min · 4 aulas', 'bg-blue-50'],
                                    ['📝', 'Estrutura da proposta', '45min · 5 aulas', 'bg-blue-50'],
                                    ['🎙️', 'A reunião de apresentação', '52min · 6 aulas', 'bg-orange-50'],
                                    ['🤜', 'Objeções e negociação', '41min · 5 aulas', 'bg-orange-50'],
                                    ['📨', 'Follow-up que fecha', '24min · 3 aulas', 'bg-gray-100'],
                                ];
                            @endphp
                            <div class="px-5 py-2">
                                @foreach ($demoModules as [$icon, $name, $sub, $bg])
                                    <div
                                         class="flex items-center gap-3 py-2.5 border-b border-black/5
                                            last:border-0 hover:bg-stone-50 transition-colors
                                            cursor-default rounded-lg -mx-1 px-1">
                                        <div
                                             class="w-11 h-11 rounded-lg {{ $bg }} flex items-center
                                                justify-center text-2xl shrink-0">
                                            {{ $icon }}</div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-bold text-gray-900 truncate">{{ $name }}
                                            </div>
                                            <div class="text-xs text-gray-400">{{ $sub }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Rodapé do card --}}
                            <div class="flex items-center justify-between px-5 py-3 border-t border-black/5">
                                <div class="text-xs text-gray-500 leading-snug">
                                    Acesso imediato<br>
                                    <strong class="text-gray-900 font-bold">Comece hoje mesmo</strong>
                                </div>
                                @if ($coursesWithContent->isNotEmpty())
                                    <a href="{{ route('courses.show', $coursesWithContent->first()->slug) }}"
                                       class="bg-accent text-white text-xs font-bold px-3.5 py-2
                                          rounded-lg hover:bg-[#bf5616] transition-colors flex items-center gap-1">
                                        Ver o curso →
                                    </a>
                                @else
                                    <button class="bg-accent text-white text-xs font-bold px-3.5 py-2 rounded-lg">
                                        Ver o curso →
                                    </button>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>



        {{-- ═══ CONTEÚDO PRINCIPAL ════════════════════════════════════════ --}}
        <div class="border-t border-stone-200">
            <div class="max-w-275 mx-auto px-6">

                {{-- ─── Cursos ─────────────────────────────────────────────── --}}
                <div class="flex items-baseline justify-between mb-5">
                    <h2 class="text-2xl font-black text-gray-900 mt-10">Cursos</h2>
                    <a href="{{ route('courses.index') }}"
                       class="text-sm font-semibold text-brand hover:underline">Ver todos →</a>
                </div>
                <div
                     class="grid grid-cols-1 lg:grid-cols-3 px-6 lg:px-0 max-w-6xl place-content-around mx-auto gap-6 lg:gap-10">
                    @forelse($coursesWithContent as $course)
                        @if (data_get($course->extra, 'featured'))
                            <a href="{{ data_get($course->extra, 'custom_url') ?: route('courses.show', $course->slug) }}"
                               title="Método de {{ $course->meta['description'] }}"
                               class="hover:bg-bottom col-span-1 h-64 xl:h-72 bg-no-repeat bg-cover bg-center rounded-xl relative shadow-sm hover:shadow-[0_8px_24px_rgba(43,58,143,0.1)] hover:-translate-y-0.5 transition-all duration-500 ease-in-out"
                               style="background-image: url({{ $course->getMedia('course-image')->first() ? $course->image('large') : asset('img/home-office-trabalho-remoto.webp') }})">
                                <div
                                     class="absolute rounded-b-xl bottom-0 inset-x-0 from-gray-900/50 bg-linear-to-t text-white text-xl font-semibold flex h-40 shadow-lg items-center justify-start pt-4 pb-2 px-6">
                                </div>
                                <div
                                     class="flex flex-col absolute bottom-0 w-full mx-auto inset-x-0 text-left bg-white/95 rounded-b-xl px-4 h-32 max-h-32 justify-center">
                                    <span style="text-wrap: balance"
                                          class="text-gray-700 text-lg font-bold mb-1.5 leading-snug">
                                        {{ $course->name }}
                                    </span>
                                    <small class="text-gray-600 text-sm">{{ $course->meta['description'] }}</small>
                                </div>
                            </a>
                        @endif
                    @empty
                        Nenhum assunto em destaque encontrado
                    @endforelse
                </div>



                {{-- ─── Destaques (estilo Substack) ───────────────────────── --}}
                <div class="py-14">

                    {{-- Topo: 3 colunas — secundários | destaque | populares --}}
                    <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-0 pb-10 mb-10">

                        {{-- Coluna central: post em destaque --}}
                        <div class="flex flex-col">
                            @if ($featuredPost)
                                <a href="{{ route('posts.show', $featuredPost->slug) }}"
                                   class="group flex flex-col gap-5">
                                    <div class="aspect-video rounded-xl overflow-hidden bg-stone-100">
                                        @if ($url = $featuredPost->image('large'))
                                            <img src="{{ $url }}" alt="{{ $featuredPost->name }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div
                                                 class="w-full h-full flex items-center justify-center text-5xl bg-brand-light">
                                                🚀</div>
                                        @endif
                                    </div>
                                    <div class="text-center">
                                        <h2
                                            class="font-display text-2xl font-black text-gray-900 leading-tight group-hover:text-brand transition-colors mb-2">
                                            {{ $featuredPost->name }}
                                        </h2>
                                        @if ($desc = data_get($featuredPost->meta, 'description'))
                                            <p class="text-sm text-gray-500 leading-relaxed max-w-md mx-auto mb-3">
                                                {{ $desc }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 uppercase tracking-widest">
                                            {{ $featuredPost->created_at->translatedFormat('d M') }}
                                            @if ($featuredPost->tag)
                                                &middot; {{ Str::upper($featuredPost->tag->name) }}
                                            @endif
                                        </p>
                                    </div>
                                </a>
                            @endif

                            {{-- Linha de baixo: 2 posts secundários --}}
                            <div class="flex flex-row gap-10 mt-10 text-center">
                                @foreach ($secondaryPosts as $post)
                                    <a href="{{ route('posts.show', $post->slug) }}"
                                       class="group flex flex-col gap-3 py-6">
                                        <div class="rounded-lg overflow-hidden bg-stone-100">
                                            @if ($url = $post->image('medium'))
                                                <img src="{{ $url }}" alt="{{ $post->name }}"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-3xl">📄
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h3
                                                class="font-bold text-gray-900 leading-snug group-hover:text-brand transition-colors mb-1">
                                                {{ $post->name }}
                                            </h3>
                                            @if ($desc = data_get($post->meta, 'description'))
                                                <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">
                                                    {{ $desc }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400 mt-2 uppercase tracking-wide">
                                                {{ $post->created_at->translatedFormat('d M') }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>



                        {{-- Coluna direita: mais populares --}}
                        <div class="pl-8">
                            <div class="flex items-baseline justify-between mb-5">
                                <span class="text-sm font-black text-gray-900 uppercase tracking-widest">Mais
                                    Populares</span>
                                {{-- <a href="{{ route('posts.index') }}" class="text-xs font-semibold text-brand hover:underline uppercase tracking-wide">Ver tudo</a> --}}
                            </div>
                            <div class="flex flex-col divide-y divide-stone-100">
                                @foreach ($popularPosts as $pop)
                                    <a href="{{ route('posts.show', $pop->slug) }}"
                                       class="group flex items-start gap-3 py-3.5 first:pt-0">
                                        <div class="flex-1 min-w-0">
                                            <h4
                                                class="text-sm font-semibold text-gray-900 leading-snug
                                               group-hover:text-brand transition-colors line-clamp-2 mb-1">
                                                {{ $pop->name }}
                                            </h4>
                                            <p class="text-xs text-gray-400 uppercase tracking-wide">
                                                {{ $pop->created_at->translatedFormat('d M, Y') }}
                                            </p>
                                        </div>
                                        <div class="w-14 h-14 rounded-md overflow-hidden shrink-0 bg-stone-100">
                                            @if ($url = $pop->image('thumb'))
                                                <img src="{{ $url }}" alt="{{ $pop->name }}"
                                                     class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Grade de posts abaixo --}}
                    @if ($gridPosts->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-10">
                            @foreach ($gridPosts as $post)
                                <a href="{{ route('posts.show', $post->slug) }}" class="group flex flex-col gap-3">
                                    <div class="aspect-video rounded-lg overflow-hidden bg-stone-100">
                                        @if ($url = $post->image('medium'))
                                            <img src="{{ $url }}" alt="{{ $post->name }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-3xl">📄
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3
                                            class="font-bold text-gray-900 leading-snug group-hover:text-brand transition-colors mb-1">
                                            {{ $post->name }}
                                        </h3>
                                        @if ($desc = data_get($post->meta, 'description'))
                                            <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
                                                {{ $desc }}</p>
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

                    {{-- CTA Banner --}}
                    <div class="mt-12 rounded-2xl px-12 py-10
                        flex flex-col sm:flex-row items-center gap-10"
                         style="background: linear-gradient(135deg, #1E2B6F 0%, #2B3A8F 60%, #3D1A00 100%)">
                        <div class="flex-1">
                            <div class="text-2xl font-black text-white tracking-tight mb-2">
                                Pronto para aprofundar?
                            </div>
                            <div class="text-sm text-white/65 leading-relaxed">
                                Acesse todos os cursos completos e comece a colocar em prática hoje mesmo.
                            </div>
                        </div>
                        <a href="{{ route('courses.index') }}"
                           class="bg-accent text-white px-6 py-3 rounded-xl text-sm font-bold
                          hover:bg-[#b84e01] transition-colors whitespace-nowrap shrink-0">
                            Ver todos os cursos →
                        </a>
                    </div>

                </div>

            </div>
        </div>

        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="absolute -top-10">
                <path fill="#F7F6F3" fill-opacity="1"
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
                                <h2 class="text-3xl font-medium tracking-tight text-white sm:text-4xl">Conheça o
                                    proComercial</h2>
                                <p class="mt-6 text-base leading-7 text-white">
                                    👋🏻 Procurando um modelo de proposta comercial, uma planilha de fluxo de caixa ou
                                    um
                                    modelo de apresentação profissional?
                                    O proComercial tem a missão de oferecer muita variedade e qualidade para tarefas
                                    profissionais do dia-a-dia: aqui você encontra desde um simples exemplo de recibo,
                                    até uma apresentação em PowerPoint de cair o queixo.
                                </p>
                                <ul role="list"
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
                                </ul>
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
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900" id="clients">Clientes atendidos
                        </h2>
                        <p class="mt-6 text-lg leading-8 text-gray-600">Ao longo dos mais de 20 anos de jornada, atendi
                            inúmeras empresas criando sites, softwares de gestão, campanhas de SEO e prestando
                            consultoria
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
                            <h2 class="text-base font-semibold leading-7 text-sky-600" id="projects">Software de
                                Gestão
                            </h2>
                            <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">SGP AgroSP</p>
                            <p class="mt-6 text-lg leading-8 text-gray-600">O SGP AgroSP integra as área da pesquisa
                                com
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
                                    <dd class="inline">Software disponível o tempo todo: do computador de mesa,
                                        notebook,
                                        tablet ou smartphone. Basta ter uma conexão com a Internet para usá-lo a
                                        qualquer
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
                                    <dd class="inline">Além de contar com transmissão de dados através do protocolo
                                        HTTPS,
                                        está em conformidade com a LGPD, mantendo as informações encriptadas no banco de
                                        dados.
                                    </dd>
                                </div>
                                <div class="relative pl-9">
                                    <dt class="inline font-semibold text-gray-900">
                                        <svg class="absolute left-1 top-1 h-5 w-5 text-sky-600"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                             fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M10 1a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 1zM5.05 3.05a.75.75 0 011.06 0l1.062 1.06A.75.75 0 116.11 5.173L5.05 4.11a.75.75 0 010-1.06zm9.9 0a.75.75 0 010 1.06l-1.06 1.062a.75.75 0 01-1.062-1.061l1.061-1.06a.75.75 0 011.06 0zM3 8a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5A.75.75 0 013 8zm11 0a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5A.75.75 0 0114 8zm-6.828 2.828a.75.75 0 010 1.061L6.11 12.95a.75.75 0 01-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zm3.594-3.317a.75.75 0 00-1.37.364l-.492 6.861a.75.75 0 001.204.65l1.043-.799.985 3.678a.75.75 0 001.45-.388l-.978-3.646 1.292.204a.75.75 0 00.74-1.16l-3.874-5.764z"
                                                  clip-rule="evenodd" />
                                        </svg>
                                        Interface amigável
                                    </dt>
                                    <dd class="inline">Elaborado com as melhores práticas de User Experience, o SGP
                                        AgroSP
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

    </div>
</x-layout>
