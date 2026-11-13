{{--
    Página de post — padrão visual "post-b" (ver README › Tipografia).
    Título em Schibsted Grotesk (sans); linha fina, corpo, subtítulos e citações em Source Serif 4 (serif).
    Estilos: resources/css/components/article.css · JS: resources/js/modules/article.js
--}}
@php
    $topic = $post->tag?->accentColor() ?? \App\Models\Tag::DEFAULT_COLOR;
@endphp

<x-layout :title="$post->name . ($post->course ? ' — ' . $post->course->name : '')"
          :description="data_get($post->meta, 'description')"
          :decoration="false">

    <div class="article-page" style="--topic: {{ $topic }}">
        <div class="article-progress" data-article-progress aria-hidden="true"><i></i></div>

        @if($post->course)
            {{-- ─── Aula de curso: mesmo padrão de leitura + índice lateral ─── --}}
            <x-container>
                <div class="grid lg:grid-cols-[1fr_280px]">

                    <article class="min-w-0 pb-20 lg:border-r border-gray-200">
                        <div class="article-measure">
                            <x-article.breadcrumb :items="[
                                ['label' => 'Cursos', 'url' => route('courses.index')],
                                ['label' => $post->course->name, 'url' => route('courses.show', $post->course->slug)],
                            ]" />

                            <x-article.header :post="$post" :shareable="false" />

                            @if($url = $post->image('large'))
                                <img src="{{ $url }}" alt="{{ $post->name }}" class="article-cover">
                            @endif

                            <div class="html-content article-prose" data-article-body>{!! $post->content !!}</div>

                            @if($isRead !== null)
                                <div class="mt-12 pt-8 border-t border-gray-200">
                                    <x-mark-read-post-button :post="$post" :is-read="$isRead" />
                                </div>
                            @endif

                            {{-- ── Navegação prev/next ───────────────────── --}}
                            @php
                                $allPosts     = $post->course->sections->flatMap(fn ($s) => $s->posts);
                                $currentIndex = $allPosts->search(fn ($p) => $p->slug === $post->slug);
                                $prevPost     = $currentIndex !== false && $currentIndex > 0 ? $allPosts[$currentIndex - 1] : null;
                                $nextPost     = $currentIndex !== false && $currentIndex < $allPosts->count() - 1 ? $allPosts[$currentIndex + 1] : null;
                            @endphp

                            <div class="mt-10 grid grid-cols-2 gap-3">
                                @if($prevPost)
                                    <a href="{{ route('posts.show', $prevPost->slug) }}"
                                       class="flex flex-col gap-0.5 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-left hover:border-gray-400 transition-colors group">
                                        <span class="text-sm font-medium text-gray-400 group-hover:text-gray-950 flex items-center gap-1">
                                            <x-heroicon-s-chevron-left class="w-3 h-3"/> Aula anterior
                                        </span>
                                        <span class="font-serif text-base font-semibold text-gray-800 leading-snug line-clamp-2">
                                            {{ $prevPost->name }}
                                        </span>
                                    </a>
                                @else
                                    <div></div>
                                @endif

                                @if($nextPost)
                                    <a href="{{ route('posts.show', $nextPost->slug) }}"
                                       class="flex flex-col gap-0.5 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-right hover:border-gray-400 transition-colors group">
                                        <span class="text-sm font-medium text-gray-400 group-hover:text-gray-950 flex items-center justify-end gap-1">
                                            Próxima aula <x-heroicon-s-chevron-right class="w-3 h-3"/>
                                        </span>
                                        <span class="font-serif text-base font-semibold text-gray-800 leading-snug line-clamp-2">
                                            {{ $nextPost->name }}
                                        </span>
                                    </a>
                                @else
                                    <div></div>
                                @endif
                            </div>
                        </div>
                    </article>

                    {{-- Índice do curso --}}
                    <x-post-index :post="$post" />

                </div>
            </x-container>

        @else
            {{-- ─── Post avulso ─────────────────────────────────────────── --}}
            <article class="pt-6">
                <div class="article-measure">
                    {{-- "Artigos" leva à lista completa da home (todos os assuntos) --}}
                    <x-article.breadcrumb :items="array_filter([
                        ['label' => 'Artigos', 'url' => route('index') . '#posts'],
                        $post->tag ? ['label' => $post->tag->name, 'url' => route('posts.index', $post->tag->slug)] : null,
                    ])" />

                    <x-article.header :post="$post" />

                    @if($url = $post->image('large'))
                        <img src="{{ $url }}" alt="{{ $post->name }}" class="article-cover">
                    @endif

                    <div class="html-content article-prose" data-article-body>{!! $post->content !!}</div>

                    @if($post->tag)
                        <div class="article-end" aria-label="Tema deste artigo">
                            <a class="article-chip" href="{{ route('posts.index', $post->tag->slug) }}">Mais sobre {{ $post->tag->name }}</a>
                        </div>
                    @endif

                    <x-article.subscribe />
                </div>
            </article>

            {{-- Mesmo convite, aberto ao rolar parte do texto (módulo JS: article) --}}
            <x-article.subscribe-popup />

            <x-article.continue-reading :posts="$recentPosts" />
        @endif
    </div>

</x-layout>
