<x-layout :title="$course->name">
<div class="-mt-30 bg-cream min-h-screen">

    {{-- ═══ HERO (imagem do curso como background) ════════════════════ --}}
    <div class="relative overflow-hidden">

        {{-- Background: imagem do curso ou gradiente padrão --}}
        @if($bgUrl = $course->image('large'))
            <div class="absolute inset-0 scale-110 blur-lg bg-cover bg-center"
                 style="background-image: url({{ $bgUrl }})"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900/70 via-gray-900/60 to-gray-900/85"></div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-brand via-[#2B3A8F] to-[#3D1A00]"></div>
            <div class="hero-grid absolute inset-0 pointer-events-none opacity-20"></div>
        @endif

        <section class="relative z-10 max-w-[1100px] mx-auto px-6 pt-[calc(5rem+72px)] pb-14">

            <a href="{{ route('courses.index') }}"
               class="inline-flex items-center gap-1.5 text-white/60 hover:text-white text-sm mb-6 transition-colors">
                <x-heroicon-o-arrow-left class="w-4 h-4"/> Todos os cursos
            </a>

            <div class="inline-flex items-center gap-1.5 text-xs font-bold tracking-widest
                        uppercase text-orange-300 bg-orange-400/20 border border-orange-400/30
                        px-3 py-1.5 rounded-full mb-4">
                Curso
            </div>

            <h1 class="font-display text-4xl lg:text-5xl font-black leading-tight tracking-tight text-white mb-4 max-w-2xl"
                style="text-wrap: balance">
                {{ $course->name }}
            </h1>

            @if($desc = data_get($course->meta, 'description'))
                <p class="text-base text-white/70 leading-relaxed max-w-xl mb-6">{{ $desc }}</p>
            @endif

            <div class="flex items-center gap-5 text-white/60 text-sm">
                <span class="flex items-center gap-1.5">
                    <x-heroicon-o-document-text class="w-4 h-4"/>
                    {{ $course->posts->count() }} {{ Str::plural('aula', $course->posts->count()) }}
                </span>
                @php
                    $readCount = $course->posts->filter(fn($p) => (bool)($p->is_read ?? false))->count();
                    $total = $course->posts->count();
                @endphp
                @if($total > 0)
                    <span class="flex items-center gap-1.5">
                        <x-heroicon-o-check-circle class="w-4 h-4"/>
                        {{ $readCount }}/{{ $total }} lidas
                    </span>
                    @if($total > 0)
                        <div class="flex items-center gap-2">
                            <div class="w-24 h-1.5 bg-white/20 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-400 rounded-full transition-all"
                                     style="width: {{ round($readCount / $total * 100) }}%"></div>
                            </div>
                            <span class="text-xs">{{ round($readCount / $total * 100) }}%</span>
                        </div>
                    @endif
                @endif
            </div>

        </section>
    </div>

    {{-- ═══ CONTEÚDO ══════════════════════════════════════════════════ --}}
    <div class="border-t border-stone-200">
        <div class="max-w-[1100px] mx-auto px-6 py-12">

            @if($course->posts && $course->posts->count())

                <div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-12">

                    {{-- Lista de aulas --}}
                    <div>
                        <div class="text-xs font-black uppercase tracking-widest text-gray-500 mb-5">
                            Conteúdo do curso
                        </div>

                        <div class="bg-white border border-stone-200 rounded-2xl overflow-hidden">
                            @foreach($course->posts as $i => $post)
                                @php
                                    $isRead = (bool) ($post->is_read ?? false);
                                @endphp
                                <a href="{{ route('posts.show', $post->slug) }}"
                                   class="flex items-center gap-4 px-5 py-4 border-b border-stone-100
                                          last:border-0 hover:bg-stone-50 transition-colors group">

                                    {{-- Número / check --}}
                                    <div class="w-8 h-8 rounded-full shrink-0 flex items-center justify-center text-sm font-bold
                                                {{ $isRead
                                                    ? 'bg-emerald-100 text-emerald-600'
                                                    : 'bg-brand/10 text-brand' }}">
                                        @if($isRead)
                                            <x-heroicon-s-check class="w-4 h-4"/>
                                        @else
                                            {{ $i + 1 }}
                                        @endif
                                    </div>

                                    {{-- Thumb --}}
                                    <div class="w-12 h-12 rounded-lg overflow-hidden shrink-0">
                                        @if($url = $post->image('thumb'))
                                            <img src="{{ $url }}" alt="{{ $post->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-brand/10 flex items-center justify-center text-xl">
                                                📄
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-bold text-gray-900 group-hover:text-brand
                                                    transition-colors leading-snug truncate">
                                            {{ $post->name }}
                                        </div>
                                        @if($desc = data_get($post->meta, 'description'))
                                            <div class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $desc }}</div>
                                        @endif
                                    </div>

                                    {{-- Badge lido/não lido --}}
                                    <div class="shrink-0">
                                        <span class="text-[11px] font-medium px-2 py-0.5 rounded
                                                     {{ $isRead ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $isRead ? 'Lido' : 'Não lido' }}
                                        </span>
                                    </div>

                                    <x-heroicon-o-chevron-right class="w-4 h-4 text-gray-300 group-hover:text-brand transition-colors shrink-0"/>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <aside class="space-y-4">

                        {{-- Progresso --}}
                        @if($total > 0)
                            <div class="bg-white border border-stone-200 rounded-xl p-4">
                                <div class="text-xs font-black uppercase tracking-widest text-gray-500 mb-3">
                                    Seu progresso
                                </div>
                                <div class="flex items-end justify-between mb-2">
                                    <span class="text-3xl font-black text-gray-900">{{ round($readCount / $total * 100) }}%</span>
                                    <span class="text-xs text-gray-400">{{ $readCount }} de {{ $total }} aulas</span>
                                </div>
                                <div class="w-full h-2 bg-stone-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-400 rounded-full transition-all"
                                         style="width: {{ round($readCount / $total * 100) }}%"></div>
                                </div>
                                @if($readCount === $total)
                                    <p class="text-xs text-emerald-600 font-semibold mt-2">
                                        ✓ Curso concluído!
                                    </p>
                                @elseif($readCount > 0)
                                    <p class="text-xs text-gray-400 mt-2">Continue de onde parou</p>
                                @else
                                    <p class="text-xs text-gray-400 mt-2">Comece pela primeira aula</p>
                                @endif
                            </div>
                        @endif

                        {{-- Outros cursos --}}
                        @if(($coursesWithContent ?? collect())->isNotEmpty())
                            <div class="bg-white border border-stone-200 rounded-xl p-4">
                                <div class="text-xs font-black uppercase tracking-widest text-gray-500 mb-3.5">
                                    Outros cursos
                                </div>
                                <div class="flex flex-col gap-3">
                                    @foreach($coursesWithContent as $c)
                                        @if($c->id !== $course->id)
                                            <a href="{{ data_get($c->extra, 'custom_url') ?: route('courses.show', $c->slug) }}"
                                               class="flex items-center gap-2.5 group">
                                                <div class="w-10 h-10 rounded-lg overflow-hidden shrink-0">
                                                    @if($thumb = $c->image('thumb'))
                                                        <img src="{{ $thumb }}" alt="{{ $c->name }}"
                                                             class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full bg-brand/10 flex items-center justify-center text-lg">📚</div>
                                                    @endif
                                                </div>
                                                <span class="text-sm font-semibold text-gray-700 group-hover:text-brand
                                                             transition-colors leading-snug line-clamp-2">
                                                    {{ $c->name }}
                                                </span>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Newsletter --}}
                        <div class="bg-brand-light border border-[#D6DAF7] rounded-xl p-4">
                            <div class="text-xs font-black uppercase tracking-widest text-brand mb-3">Newsletter</div>
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

            @else
                <div class="text-center py-20">
                    <div class="text-5xl mb-4">📭</div>
                    <p class="text-gray-500">Este curso ainda não possui aulas publicadas.</p>
                    <a href="{{ route('courses.index') }}"
                       class="inline-block mt-4 text-sm font-semibold text-brand hover:underline">
                        Ver outros cursos →
                    </a>
                </div>
            @endif

        </div>
    </div>

</div>
</x-layout>
