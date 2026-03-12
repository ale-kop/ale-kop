<x-layout :title="$post->name . ($post->course ? ' — ' . $post->course->name : '')">

    {{-- ── Breadcrumb full-width (só em aulas de curso) ────────────── --}}
    @if($post->course)
        <div class="w-full bg-brand-light dark:bg-indigo-950/50 border-b border-[#D6DAF7] dark:border-indigo-900/40">
            <div class="max-w-7xl mx-auto px-4 xl:px-0 py-2.5 flex items-center gap-1.5 text-sm text-brand dark:text-indigo-300 font-medium">
                <a href="{{ route('courses.index') }}" class="hover:underline opacity-70 hover:opacity-100 transition-opacity">Cursos</a>
                <span class="opacity-30">›</span>
                <a href="{{ route('courses.show', $post->course->slug) }}" class="hover:underline opacity-80 hover:opacity-100 transition-opacity">{{ $post->course->name }}</a>
                <span class="opacity-30">›</span>
                <span class="opacity-60">{{ $post->name }}</span>
            </div>
        </div>
    @endif

    <x-container>
        <div class="grid @if($post->course) lg:grid-cols-[1fr_280px] @lese grid-cols-1 @endif">
            <article class="min-w-0 py-10 pr-12 pb-20 @if($post->course) lg:border-r border-gray-200 dark:border-gray-700 @endif">

                {{-- ── Cabeçalho ────────────────────────────────────────── --}}
                <header class="mb-6 space-y-3">
                    {{-- Meta: tag + status --}}
                    <div class="flex flex-wrap items-center gap-2">
                        @if($post->tag)
                            <a href="{{ route('posts.index', ['tagSlug' => $post->tag->slug]) }}"
                               class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                      bg-accent-light text-accent dark:bg-orange-900/40 dark:text-orange-300
                                      hover:opacity-80 transition-opacity">
                                {{ $post->tag->name }}
                            </a>
                        @endif

                        @if($post->course)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                         bg-brand-light text-brand dark:bg-indigo-900/40 dark:text-indigo-300">
                                Aula do curso
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                         bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                Post avulso
                            </span>
                        @endif
                    </div>

                    {{-- Título --}}
                    <h1 class="text-3xl font-display font-bold tracking-tight leading-tight text-gray-900 dark:text-gray-100">
                        {{ $post->name }}
                    </h1>
                </header>

                {{-- ── Hero image (compacta, com fallback gradiente) ─────── --}}
                @if($url = $post->image('large'))
                    <div class="mb-8 rounded-xl overflow-hidden" style="max-height:220px">
                        <img src="{{ $url }}"
                             alt="{{ $post->name }}"
                             class="w-full h-full object-cover object-center"
                             style="max-height:220px">
                    </div>
                @elseif($post->course)
                    <div class="mb-8 rounded-xl overflow-hidden flex items-center justify-center"
                         style="height:160px; background: linear-gradient(135deg, #1E2B6F 0%, #2B3A8F 55%, #D95F02 100%);">
                        <span class="text-white/80 text-xs font-semibold tracking-widest uppercase">
                            {{ $post->course->name }}
                        </span>
                    </div>
                @endif

                {{-- ── Conteúdo ─────────────────────────────────────────── --}}
                <div class="html-content max-w-none">{!! $post->content !!}</div>

                {{-- ── Marcar como lido — no fim, onde faz sentido ─────── --}}
                @if($isRead !== null)
                    <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <x-mark-read-post-button :post="$post" :is-read="$isRead" />
                    </div>
                @endif

                {{-- ── Navegação prev/next (somente em aulas de curso) ──── --}}
                @if($post->course)
                    @php
                        $allPosts     = $post->course->sections->flatMap(fn ($s) => $s->posts);
                        $currentIndex = $allPosts->search(fn ($p) => $p->slug === $post->slug);
                        $prevPost     = $currentIndex > 0 ? $allPosts[$currentIndex - 1] : null;
                        $nextPost     = $currentIndex < $allPosts->count() - 1 ? $allPosts[$currentIndex + 1] : null;
                    @endphp

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        {{-- Anterior --}}
                        @if($prevPost)
                            <a href="{{ route('posts.show', $prevPost->slug) }}"
                               class="flex flex-col gap-0.5 rounded-lg border border-gray-200 dark:border-gray-700
                                      bg-white dark:bg-gray-800/60 px-4 py-3
                                      text-left hover:border-brand dark:hover:border-indigo-600
                                      transition-colors group">
                                <span class="text-[0.7rem] font-semibold uppercase tracking-widest
                                             text-gray-400 dark:text-gray-500 group-hover:text-brand dark:group-hover:text-indigo-400
                                             flex items-center gap-1">
                                    <x-heroicon-o-arrow-left class="w-3 h-3"/> Anterior
                                </span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200 leading-snug line-clamp-2">
                                    {{ $prevPost->name }}
                                </span>
                            </a>
                        @else
                            <div></div>
                        @endif

                        {{-- Próxima --}}
                        @if($nextPost)
                            <a href="{{ route('posts.show', $nextPost->slug) }}"
                               class="flex flex-col gap-0.5 rounded-lg
                                      bg-brand-light dark:bg-indigo-950/60
                                      border border-[#D6DAF7] dark:border-indigo-900/50
                                      px-4 py-3 text-right
                                      hover:border-brand dark:hover:border-indigo-600
                                      transition-colors group">
                                <span class="text-[0.7rem] font-semibold uppercase tracking-widest
                                             text-brand/60 dark:text-indigo-400/70 group-hover:text-brand dark:group-hover:text-indigo-300
                                             flex items-center justify-end gap-1">
                                    Próxima aula <x-heroicon-o-arrow-right class="w-3 h-3"/>
                                </span>
                                <span class="text-sm font-medium text-brand dark:text-indigo-300 leading-snug line-clamp-2">
                                    {{ $nextPost->name }}
                                </span>
                            </a>
                        @else
                            <div></div>
                        @endif
                    </div>
                @else
                    <p class="mt-8">
                        <a href="{{ url()->previous() }}"
                           class="inline-flex items-center gap-1.5 text-sm text-brand hover:underline dark:text-brand-light">
                            <x-heroicon-o-arrow-left class="w-3.5 h-3.5"/> Voltar
                        </a>
                    </p>
                @endif

            </article>

            @if($post->course)
                <x-post-index :post="$post" />
            @endif
        </div>
    </x-container>
</x-layout>
