@php
    $tagName = isset($tag) ? $tag->name : Str::of($tagSlug)->title()->replace('-', ' ');
    $tagDesc = data_get($tag?->meta, 'description');
@endphp

<x-layout :title="$tagName">
<main class="overflow-hidden bg-white">

    {{-- ═══ HERO ════════════════════════════════════════════════════════ --}}
    <x-container>
        <p class="font-mono text-xs/5 font-semibold tracking-widest text-gray-500 uppercase">
            Assunto
        </p>
        <h1 class="mt-2 text-4xl font-medium tracking-tighter text-pretty text-gray-950 sm:text-6xl">
            {{ $tagName }}
        </h1>
        @if($tagDesc)
            <p class="mt-4 max-w-3xl text-2xl font-medium text-gray-500">
                {{ $tagDesc }}
            </p>
        @endif
    </x-container>

    {{-- ═══ FILTRO + POSTS ═══════════════════════════════════════════════ --}}
    <x-container class="mt-16 pb-24">

        {{-- Filtro por tag (estilo radiant: link de "Todos" + tags) --}}
        @if(($tagsWithContent ?? collect())->isNotEmpty())
            <div class="flex flex-wrap items-center gap-x-5 gap-y-2">
                <span class="text-sm/6 font-medium text-gray-950/50">Assuntos</span>
                @foreach($tagsWithContent as $t)
                    <a href="{{ route('posts.index', $t->slug) }}"
                       class="text-sm/6 font-medium {{ $t->slug === ($tagSlug ?? $tag?->slug) ? 'text-gray-800 underline decoration-gray-400 font-semibold underline-offset-4' : 'text-gray-500 hover:text-gray-950' }}">
                        {{ $t->name }}
                    </a>
                @endforeach
            </div>
        @endif

        @if(isset($posts) && $posts->count())

            <div class="mt-6">
                @foreach($posts as $post)
                    @php
                        $desc     = data_get($post->meta, 'description');
                    @endphp
                    <div class="relative group gap-10 grid grid-cols-1 border-b border-b-gray-100 py-10 first:border-t first:border-t-gray-200 max-sm:gap-3 sm:grid-cols-[320px_1fr]">
                        
                            
                        <img src="{{ $post->image('large') }}" class="rounded-lg border border-gray-200 group-hover:border-gray-300/80 group-hover:shadow-xl transition-all duration-300 ease-in-out">
                            
                        
                        <div class="sm:max-w-2xl">
                            <h2 class="text-3xl font-serif font-semibold text-gray-800 group-hover:text-brand">{{ $post->name }}</h2>
                            @if($desc)
                                <p class="mt-2 text-base text-gray-500">{{ $desc }}</p>
                            @endif
                            <div class="mt-4">
                                <a href="{{ route('posts.show', $post->slug) }}"
                                   class="flex items-center gap-1 text-sm/5 font-medium text-gray-950">
                                    <span class="absolute inset-0"></span>
                                    Ler mais
                                    <x-heroicon-s-chevron-right class="size-4 text-gray-400"/>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @else

            <p class="mt-10 text-gray-500">Nenhum artigo encontrado para "{{ $tagName }}".</p>

        @endif

    </x-container>

</main>
</x-layout>
