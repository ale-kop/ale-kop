<x-layout :title="$post->name . ($post->course ? ' — ' . $post->course->name : '')">

    @if($post->course)
        {{-- ─── Aula de curso: layout com sidebar lateral ─────────── --}}
        <x-container>
            <div class="grid lg:grid-cols-[1fr_280px]">

                <article class="min-w-0 pr-0 lg:pr-12 pb-20 lg:border-r border-gray-200">

                    {{-- ── Cabeçalho radiant ───────────────────────── --}}
                    <header class="mb-10">
                        <p class="font-mono text-xs/5 font-semibold tracking-widest text-gray-500 uppercase">
                            <a href="{{ route('courses.index') }}" class="hover:text-gray-950 transition-colors">Cursos</a>
                            <span class="mx-1 text-gray-300">/</span>
                            <a href="{{ route('courses.show', $post->course->slug) }}" class="hover:text-gray-950 transition-colors">{{ $post->course->name }}</a>
                        </p>
                        <h1 class="mt-2 font-serif text-4xl font-medium tracking-tighter text-pretty text-gray-900 sm:text-5xl">
                            {{ $post->name }}
                        </h1>
                        {{-- @if($post->tag)
                            <div class="mt-4 flex flex-wrap gap-2">
                                <a href="{{ route('posts.index', $post->tag->slug) }}"
                                   class="rounded-full border border-dotted border-gray-300 bg-gray-50 px-2 text-sm/6 font-medium text-gray-500 hover:text-gray-950">
                                    {{ $post->tag->name }}
                                </a>
                            </div>
                        @endif --}}
                    </header>

                    {{-- ── Hero image ──────────────────────────────── --}}
                    @if($url = $post->image('large'))
                        <img src="{{ $url }}" alt="{{ $post->name }}"
                             class="mb-10 aspect-3/2 w-full rounded-2xl object-cover shadow-xl">
                    @endif

                    {{-- ── Conteúdo ────────────────────────────────── --}}
                    <div class="html-content max-w-none text-gray-700">{!! $post->content !!}</div>

                    {{-- ── Marcar como lido ───────────────────────── --}}
                    @if($isRead !== null)
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <x-mark-read-post-button :post="$post" :is-read="$isRead" />
                        </div>
                    @endif

                    {{-- ── Navegação prev/next ───────────────────── --}}
                    @php
                        $allPosts     = $post->course->sections->flatMap(fn ($s) => $s->posts);
                        $currentIndex = $allPosts->search(fn ($p) => $p->slug === $post->slug);
                        $prevPost     = $currentIndex > 0 ? $allPosts[$currentIndex - 1] : null;
                        $nextPost     = $currentIndex < $allPosts->count() - 1 ? $allPosts[$currentIndex + 1] : null;
                    @endphp

                    <div class="mt-10 grid grid-cols-2 gap-3">
                        @if($prevPost)
                            <a href="{{ route('posts.show', $prevPost->slug) }}"
                               class="flex flex-col gap-0.5 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-left hover:border-gray-400 transition-colors group">
                                <span class="text-[0.7rem] font-semibold uppercase tracking-widest text-gray-400 group-hover:text-gray-950 flex items-center gap-1">
                                    <x-heroicon-s-chevron-left class="w-3 h-3"/> Anterior
                                </span>
                                <span class="text-sm font-medium text-gray-700 leading-snug line-clamp-2">
                                    {{ $prevPost->name }}
                                </span>
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if($nextPost)
                            <a href="{{ route('posts.show', $nextPost->slug) }}"
                               class="flex flex-col gap-0.5 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-right hover:border-gray-400 transition-colors group">
                                <span class="text-[0.7rem] font-semibold uppercase tracking-widest text-gray-400 group-hover:text-gray-950 flex items-center justify-end gap-1">
                                    Próxima aula <x-heroicon-s-chevron-right class="w-3 h-3"/>
                                </span>
                                <span class="text-sm font-medium text-gray-700 leading-snug line-clamp-2">
                                    {{ $nextPost->name }}
                                </span>
                            </a>
                        @else
                            <div></div>
                        @endif
                    </div>

                </article>

                {{-- Sidebar do curso permanece como está --}}
                <x-post-index :post="$post" />

            </div>
        </x-container>

    @else
        {{-- ─── Post avulso: layout radiant blog post ────────────── --}}
        <x-container>
            <div class="text-center">
                <p class="font-mono text-xs/5 font-semibold tracking-widest text-gray-500 uppercase">
                    {{ optional($post->created_at)->translatedFormat('l, j \\d\\e F \\d\\e Y') }}
                </p>
                <h1 class="mt-6 text-4xl font-serif font-medium tracking-tighter text-pretty text-gray-950 sm:text-5xl">
                    {{ $post->name }}
                </h1>
            </div>

            <div class="mt-10 gap-8 pb-24">
                {{-- <div class="flex flex-wrap items-center gap-8 max-lg:justify-between lg:flex-col lg:items-start">
                    @if($post->tag)
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('posts.index', $post->tag->slug) }}"
                               class="rounded-full border border-dotted border-gray-300 bg-gray-50 px-2 text-sm/6 font-medium text-gray-500 hover:text-gray-950">
                                {{ $post->tag->name }}
                            </a>
                        </div>
                    @endif
                    sidebar...
                </div> --}}

                <div class="text-gray-700">
                    <div class="max-w-3xl lg:px-4 md:mx-auto">

                        @if($url = $post->image('large'))
                            <img src="{{ $url }}" alt="{{ $post->name }}"
                                 class="mb-10 aspect-3/2 w-full rounded-2xl object-cover shadow-xl">
                        @endif

                        <div class="html-content font-serif text-xl">{!! $post->content !!}</div>

                        <div class="mt-10">
                            <a href="{{ $post->tag ? route('posts.index', $post->tag->slug) : url()->previous() }}"
                               class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-1.5 text-sm font-medium text-gray-950 hover:bg-gray-50 transition-colors">
                                <x-heroicon-s-chevron-left class="size-4"/>
                                Voltar
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </x-container>
    @endif

</x-layout>
