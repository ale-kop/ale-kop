<x-layout :title="(isset($tag) ? $tag->name : Str::of($tagSlug)->title()->replace('-',' '))">
    <x-container class="pt-16">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl font-bold mb-2">{{ isset($tag) ? $tag->name : Str::of($tagSlug)->title()->replace('-',' ') }}</h1>
            @isset($tag->meta['description'])
                <p class="text-gray-600 dark:text-gray-300">{{ $tag->meta['description'] }}</p>
            @endisset
        </div>
        <div class="max-w-3xl mx-auto mt-10">
            @if(isset($posts) && $posts->count())
                <x-posts-list :posts="$posts" />
            @else
                <div class="text-center text-gray-600 dark:text-gray-300">
                    <p class="font-semibold">Nenhum post encontrado.</p>
                    <p class="mt-4">Veja outros assuntos:</p>
                    <ul class="mt-2 space-y-1">
                        @foreach(($tagsWithContent ?? []) as $tag)
                            <li>
                                <a href="{{ route('posts.index', $tag->slug) }}" class="text-blue-600 hover:underline">{{ $tag->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </x-container>
</x-layout>
