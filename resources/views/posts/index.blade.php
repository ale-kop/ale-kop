@php
    $tagName = isset($tag) ? $tag->name : Str::of($tagSlug)->title()->replace('-', ' ');
    $tagDesc = data_get($tag?->meta, 'description');
@endphp

<x-layout :title="$tagName">
<div class="-mt-30 bg-cream min-h-screen">

    {{-- ═══ HERO ════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden">
        <div class="w-full absolute top-0 h-[360px] z-1 opacity-60
                    bg-gradient-to-b from-white via-teal-50 to-transparent"></div>
        <div class="hero-grid absolute inset-0 pointer-events-none z-2 animate-ambient-fade-in"></div>

        <section class="relative z-10 max-w-[1100px] mx-auto px-6 pt-[calc(4.5rem+72px)] pb-10">
            <div class="inline-flex items-center gap-1.5 text-xs font-bold tracking-widest
                        uppercase text-accent bg-accent/10 border border-accent/20
                        px-3 py-1.5 rounded-full mb-4 animate-fade-up" style="animation-delay:.1s">
                Assunto
            </div>
            <h1 class="font-display text-4xl font-black leading-tight tracking-tight text-gray-900 mb-3
                       animate-fade-up" style="animation-delay:.2s">
                {{ $tagName }}
            </h1>
            @if($tagDesc)
                <p class="text-base text-gray-500 leading-relaxed max-w-xl
                          animate-fade-up" style="animation-delay:.3s">
                    {{ $tagDesc }}
                </p>
            @endif
        </section>
    </div>

    {{-- ═══ CONTEÚDO ══════════════════════════════════════════════════ --}}
    <div class="border-t border-stone-200">
        <div class="max-w-[1100px] mx-auto px-6 py-12">

            @if(isset($posts) && $posts->count())

                <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-12">

                    {{-- Posts (estilo Substack) --}}
                    <div>
                        <div class="flex items-baseline justify-between mb-5">
                            <h2 class="text-sm font-black uppercase tracking-widest text-gray-500">
                                {{ $posts->count() }} {{ Str::plural('artigo', $posts->count()) }}
                            </h2>
                        </div>

                        <div class="flex flex-col divide-y divide-stone-200">
                            @foreach($posts as $post)
                                @php
                                    $readable = $post->course_id !== null;
                                    $isRead   = $readable ? (bool) ($post->is_read ?? false) : false;
                                @endphp
                                <div class="flex items-start gap-5 py-5 first:pt-0">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                            @if($post->course)
                                                <a href="{{ route('courses.show', $post->course->slug) }}"
                                                   class="inline-flex items-center gap-1 text-xs font-semibold
                                                          text-brand bg-brand-light border border-brand/20
                                                          px-2 py-0.5 rounded-full hover:bg-brand hover:text-white transition-colors">
                                                    <x-heroicon-o-academic-cap class="w-3 h-3"/>
                                                    {{ $post->course->name }}
                                                </a>
                                            @endif
                                            @if($post->tag && $post->tag->slug !== $tagSlug)
                                                <span class="text-xs font-bold text-accent bg-accent/10
                                                             px-2 py-0.5 rounded-full uppercase tracking-wider">
                                                    {{ $post->tag->name }}
                                                </span>
                                            @endif
                                            @if($readable)
                                                <span class="text-[11px] font-medium px-2 py-0.5 rounded
                                                    {{ $isRead ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                                    {{ $isRead ? 'Lido' : 'Não lido' }}
                                                </span>
                                            @endif
                                        </div>
                                        <a href="{{ route('posts.show', $post->slug) }}"
                                           class="block text-base font-bold text-gray-900 mb-1.5
                                                  leading-snug hover:text-brand transition-colors">
                                            {{ $post->name }}
                                        </a>
                                        @if($desc = data_get($post->meta, 'description'))
                                            <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">{{ $desc }}</p>
                                        @endif
                                    </div>
                                    <div class="w-20 h-16 rounded-lg overflow-hidden shrink-0">
                                        @if($url = $post->image('thumb'))
                                            <img src="{{ $url }}" alt="{{ $post->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center
                                                        text-2xl bg-brand-light">📄</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <aside class="space-y-4">

                        {{-- Widget: explorar por tag --}}
                        <div class="bg-white border border-stone-200 rounded-xl p-4">
                            <div class="text-xs font-black uppercase tracking-widest text-gray-500 mb-3.5">
                                Outros assuntos
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                @forelse(($tagsWithContent ?? []) as $t)
                                    <a href="{{ route('posts.index', $t->slug) }}"
                                       class="text-xs font-medium px-2.5 py-1 rounded-full border transition-colors
                                              {{ $t->slug === ($tagSlug ?? $tag?->slug)
                                                  ? 'bg-brand text-white border-brand'
                                                  : 'bg-cream border-stone-200 text-gray-500 hover:bg-brand-light hover:border-brand hover:text-brand' }}">
                                        {{ $t->name }}
                                    </a>
                                @empty
                                    <span class="text-xs text-gray-400">Nenhuma tag</span>
                                @endforelse
                            </div>
                        </div>

                        {{-- Widget: newsletter --}}
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

                {{-- Empty state --}}
                <div class="text-center py-20">
                    <div class="text-5xl mb-4">🔍</div>
                    <p class="font-semibold text-gray-700 mb-1">Nenhum artigo encontrado para "{{ $tagName }}"</p>
                    <p class="text-sm text-gray-500 mb-6">Explore outros assuntos:</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        @foreach(($tagsWithContent ?? []) as $t)
                            <a href="{{ route('posts.index', $t->slug) }}"
                               class="text-xs font-medium px-3 py-1.5 rounded-full border border-stone-200
                                      bg-white text-gray-600 hover:bg-brand-light hover:border-brand hover:text-brand transition-colors">
                                {{ $t->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

            @endif

        </div>
    </div>

</div>
</x-layout>
