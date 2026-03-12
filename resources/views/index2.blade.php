<x-layout>
{{-- ░░ Wrapper com fundo creme ░░ --}}
<div class="-mt-30 bg-cream min-h-screen">
    
    {{-- ═══ HERO ═══════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden">
        <div class="w-full absolute top-0 h-[600px] z-1 opacity-60
                    bg-gradient-to-b from-white via-teal-50 to-transparent"></div>
        {{-- Grade quadriculada --}}
        <div class="hero-grid absolute inset-0 pointer-events-none z-2 animate-ambient-fade-in"></div>

        <section class="relative z-10 max-w-[1100px] mx-auto px-6 pt-[calc(7.5rem+72px)] pb-16
                        grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- Texto esquerdo --}}
            <div>
                <div class="inline-flex items-center gap-1.5 text-xs font-bold tracking-widest
                            uppercase text-accent bg-accent/10 border border-accent/20
                            px-3 py-1.5 rounded-full mb-5
                            animate-fade-up" style="animation-delay:.1s">
                    ✦ Para profissionais de vendas, marketing e gestão
                </div>

                <h1 class="font-display text-4xl font-black leading-tight tracking-tight text-gray-900 mb-4
                           animate-fade-up" style="animation-delay:.2s">
                    <em class="not-italic text-brand">Do primeiro <span class="mark-highlight">contato</span> ao fechamento</em>
                </h1>

                <p class="text-base text-gray-500 leading-relaxed max-w-sm mb-8
                          animate-fade-up" style="animation-delay:.3s">
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
                            animate-fade-up" style="animation-delay:.5s">
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
                            animate-fade-up" style="animation-delay:.55s">
                    <div class="flex">
                        <div class="w-8 h-8 rounded-full border-2 border-cream bg-brand
                                    flex items-center justify-center text-xs font-extrabold text-white">RM</div>
                        <div class="w-8 h-8 rounded-full border-2 border-cream bg-accent
                                    flex items-center justify-center text-xs font-extrabold text-white -ml-2">CA</div>
                        <div class="w-8 h-8 rounded-full border-2 border-cream bg-done
                                    flex items-center justify-center text-xs font-extrabold text-white -ml-2">JP</div>
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
                        animate-fade-up" style="animation-delay:.2s">

                {{-- Orbs coloridos --}}
                <div class="absolute w-80 h-80 rounded-full pointer-events-none opacity-0 animate-orb-1 -top-10 -right-2.5"
                     style="background:radial-gradient(circle,rgba(43,76,126,0.28) 0%,transparent 65%);filter:blur(45px)"></div>
                <div class="absolute w-60 h-60 rounded-full pointer-events-none opacity-0 animate-orb-2 bottom-2.5 -left-5"
                     style="background:radial-gradient(circle,rgba(108, 45, 127, 0.22) 0%,transparent 65%);filter:blur(38px)"></div>
                <div class="absolute w-44 h-44 rounded-full pointer-events-none opacity-0 animate-orb-3 top-[45%] -right-8"
                     style="background:radial-gradient(circle,rgba(74,124,89,0.18) 0%,transparent 70%);filter:blur(32px)"></div>

                {{-- Card flutuante --}}
                <div class="relative z-10 w-full max-w-sm bg-white rounded-2xl overflow-visible"
                     style="box-shadow:0 24px 60px rgba(0,0,0,0.12),0 4px 16px rgba(0,0,0,0.06),0 0 0 1px rgba(0,0,0,0.04);animation:card-entry 0.8s cubic-bezier(0.22,1,0.36,1) both,card-float 8s ease-in-out 0.8s infinite">

                    {{-- Badge "ao vivo" --}}
                    <div class="absolute -top-3.5 -right-6 z-20 bg-white border border-stone-100 rounded-full
                                px-3 py-1.5 text-xs font-bold text-gray-900 shadow-md
                                flex items-center gap-1.5 whitespace-nowrap animate-badge-float">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-badge-dot"></span>
                        3 alunos assistindo agora
                    </div>

                    <div class="absolute -bottom-4 left-8 z-20 bg-white border border-stone-100 rounded-full
                                px-3 py-1.5 text-xs font-bold text-gray-900 shadow-md
                                flex items-center gap-1.5 whitespace-nowrap animate-badge-float">
                        <span class="w-2 h-2 rounded-full bg-orange-500 animate-badge-dot" style="animation-delay:.6s"></span>
                        Últimas vagas
                    </div>

                    <div class="rounded-2xl overflow-hidden">

                        {{-- Cabeçalho do card --}}
                        <div class="relative px-5 pt-5 pb-4 overflow-hidden bg-brand">
                            <div class="absolute -top-10 -right-10 w-36 h-36 rounded-full bg-white/5 pointer-events-none"></div>
                            <div class="absolute -bottom-8 left-5 w-24 h-24 rounded-full bg-white/[0.03] pointer-events-none"></div>
                            {{-- Shine line --}}
                            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/35 to-transparent"></div>

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
                                ['🔍', 'Diagnóstico do cliente',    '38min · 4 aulas', 'bg-blue-50'],
                                ['📝', 'Estrutura da proposta',     '45min · 5 aulas', 'bg-blue-50'],
                                ['🎙️', 'A reunião de apresentação', '52min · 6 aulas', 'bg-orange-50'],
                                ['🤜', 'Objeções e negociação',     '41min · 5 aulas', 'bg-orange-50'],
                                ['📨', 'Follow-up que fecha',       '24min · 3 aulas', 'bg-gray-100'],
                            ];
                        @endphp
                        <div class="px-5 py-2">
                            @foreach($demoModules as [$icon, $name, $sub, $bg])
                                <div class="flex items-center gap-3 py-2.5 border-b border-black/5
                                            last:border-0 hover:bg-stone-50 transition-colors
                                            cursor-default rounded-lg -mx-1 px-1">
                                    <div class="w-11 h-11 rounded-lg {{ $bg }} flex items-center
                                                justify-center text-2xl shrink-0">{{ $icon }}</div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-bold text-gray-900 truncate">{{ $name }}</div>
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
                            @if($coursesWithContent->isNotEmpty())
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
    <div class="max-w-[1100px] mx-auto px-6">

        {{-- ─── Cursos ─────────────────────────────────────────────── --}}
        <div class="py-14 border-b border-stone-200">

            <div class="flex items-baseline justify-between mb-5">
                <h2 class="text-2xl font-black text-gray-900">Cursos</h2>
                <a href="{{ route('courses.index') }}"
                   class="text-sm font-semibold text-brand hover:underline">Ver todos →</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($coursesWithContent->take(3) as $course)
                    <a href="{{ data_get($course->extra, 'custom_url') ?: route('courses.show', $course->slug) }}"
                       class="bg-white border border-stone-200 rounded-xl overflow-hidden
                              hover:shadow-[0_8px_24px_rgba(43,58,143,0.1)] hover:-translate-y-0.5
                              transition-all duration-200 block">

                        {{-- Thumb --}}
                        <div class="h-36 relative overflow-hidden">
                            @if($url = $course->image('large'))
                                <img src="{{ $url }}" alt="{{ $course->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-4xl"
                                     style="background: linear-gradient(135deg,#1E2B6F,#2B3A8F)">
                                    📚
                                </div>
                            @endif
                            <span class="absolute top-2.5 left-2.5 bg-brand text-white
                                         text-xs font-bold px-2 py-0.5 rounded-full tracking-wider">
                                📄 {{ $course->posts->count() }} aulas
                            </span>
                        </div>

                        {{-- Body --}}
                        <div class="px-4 py-4">
                            <div class="text-base font-bold text-gray-900 mb-1.5 leading-snug">
                                {{ $course->name }}
                            </div>
                            <div class="text-sm text-gray-500 leading-relaxed line-clamp-2">
                                {{ data_get($course->meta, 'description') }}
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="col-span-3 text-sm text-gray-500">Nenhum curso disponível.</p>
                @endforelse
            </div>
        </div>

        {{-- ─── Posts + Sidebar ────────────────────────────────────── --}}
        <div class="py-14">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-12">

                {{-- Posts (estilo Substack) --}}
                <div>
                    <div class="flex items-baseline justify-between mb-5">
                        <h2 class="text-2xl font-black text-gray-900">Destaques</h2>
                        <a href="#" class="text-sm font-semibold text-brand hover:underline">Ver todos →</a>
                    </div>

                    <div class="flex flex-col divide-y divide-stone-200">

                        @if($featuredPost)
                            <div class="flex items-start gap-5 py-5 first:pt-0">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        @if($featuredPost->tag)
                                            <span class="text-xs font-bold text-accent bg-accent-light
                                                         px-2 py-0.5 rounded-full uppercase tracking-wider">
                                                {{ $featuredPost->tag->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <a href="{{ route('posts.show', $featuredPost->slug) }}"
                                       class="block text-base font-bold text-gray-900 mb-1.5
                                              leading-snug hover:text-brand transition-colors">
                                        {{ $featuredPost->name }}
                                    </a>
                                    <p class="text-sm text-gray-500 leading-relaxed">
                                        {{ data_get($featuredPost->meta, 'description') }}
                                    </p>
                                </div>
                                <div class="w-20 h-16 rounded-lg overflow-hidden shrink-0">
                                    @if($url = $featuredPost->image('thumb'))
                                        <img src="{{ $url }}" alt="{{ $featuredPost->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center
                                                    text-2xl bg-brand-light">🚀</div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @foreach($latestTwoPosts as $post)
                            <div class="flex items-start gap-5 py-5">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        @if($post->tag)
                                            <span class="text-xs font-bold text-accent bg-accent-light
                                                         px-2 py-0.5 rounded-full uppercase tracking-wider">
                                                {{ $post->tag->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <a href="{{ route('posts.show', $post->slug) }}"
                                       class="block text-base font-bold text-gray-900 mb-1.5
                                              leading-snug hover:text-brand transition-colors">
                                        {{ $post->name }}
                                    </a>
                                    <p class="text-sm text-gray-500 leading-relaxed">
                                        {{ data_get($post->meta, 'description') }}
                                    </p>
                                </div>
                                <div class="w-20 h-16 rounded-lg overflow-hidden shrink-0">
                                    @if($url = $post->image('thumb'))
                                        <img src="{{ $url }}" alt="{{ $post->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center
                                                    text-2xl bg-accent-light">📄</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                {{-- Sidebar --}}
                <aside>

                    {{-- Widget: mais lidos --}}
                    <div class="bg-white border border-stone-200 rounded-xl p-4 mb-4">
                        <div class="text-xs font-black uppercase tracking-widest
                                    text-gray-500 mb-3.5">Mais lidos</div>
                        <div class="flex flex-col gap-3">
                            @foreach($latestTwoPosts as $i => $p)
                                <div class="flex items-start gap-2.5">
                                    <div class="w-5 h-5 rounded-full bg-brand flex items-center
                                                justify-center text-xs font-black text-white
                                                shrink-0 mt-0.5">
                                        {{ $i + 1 }}
                                    </div>
                                    <div>
                                        <a href="{{ route('posts.show', $p->slug) }}"
                                           class="text-sm font-semibold text-gray-900 leading-snug
                                                  hover:text-brand transition-colors">
                                            {{ Str::limit($p->name, 50) }}
                                        </a>
                                        @if($p->tag)
                                            <div class="text-xs text-gray-400 mt-0.5">
                                                {{ $p->tag->name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Widget: tags --}}
                    <div class="bg-white border border-stone-200 rounded-xl p-4 mb-4">
                        <div class="text-xs font-black uppercase tracking-widest
                                    text-gray-500 mb-3.5">Explorar por tag</div>
                        <div class="flex flex-wrap gap-1.5">
                            @forelse(($tagsWithContent ?? []) as $tag)
                                <a href="{{ route('posts.index', $tag->slug) }}"
                                   class="bg-cream border border-stone-200 text-gray-500 text-xs
                                          font-medium px-2.5 py-1 rounded-full
                                          hover:bg-brand-light hover:border-brand hover:text-brand
                                          transition-colors">
                                    {{ $tag->name }}
                                </a>
                            @empty
                                <span class="text-xs text-gray-400">Nenhuma tag</span>
                            @endforelse
                        </div>
                    </div>

                    {{-- Widget: newsletter --}}
                    <div class="bg-brand-light border border-[#D6DAF7] rounded-xl p-4">
                        <div class="text-xs font-black uppercase tracking-widest
                                    text-brand mb-3">Newsletter</div>
                        <p class="text-sm text-brand/80 mb-3 leading-relaxed">
                            Novos posts e aulas direto no seu e-mail.
                        </p>
                        <input type="email" placeholder="seu@email.com"
                               class="w-full border border-[#D6DAF7] bg-white px-3 py-2 rounded-lg
                                      text-sm mb-2 outline-none focus:border-brand transition-colors">
                        <button class="w-full bg-brand text-white text-sm font-semibold py-2
                                       rounded-lg hover:bg-[#1d2c70] transition-colors">
                            Assinar grátis
                        </button>
                    </div>

                </aside>
            </div>

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

</div>
</x-layout>
