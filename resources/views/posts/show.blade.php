<x-layout :title="$post->name">
    <x-container class="pt-16">
        <div class="flex gap-6">
            <x-post-index :post="$post" :recentPosts="$recentPosts ?? collect()" />
            <article class="space-y-6 flex-1 max-w-3xl mx-auto">
                <h1 class="text-4xl font-bold flex items-center gap-2 text-gray-700 dark:text-gray-200">
                    {{ $post->name }}
                    @unless($post->course)
                        <span class="inline-flex items-center text-xs font-medium px-2 py-0.5 rounded bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">Post avulso</span>
                    @endunless
                </h1>
                @if($url = $post->image('large'))
                    <img src="{{ $url }}" alt="{{ $post->name }}" class="w-full rounded-lg">
                @endif
                <div class="html-content max-w-none">{!! $post->content !!}</div>
                @if($post->tag)
                    <p class="text-sm text-gray-600 dark:text-gray-300">Tag: <a class="text-blue-600 hover:underline" href="{{ route('posts.index', ['tagSlug' => $post->tag->slug]) }}">{{ $post->tag->name }}</a></p>
                @endif
                <p><a href="{{ url()->previous() }}" class="text-blue-600 hover:underline">Voltar</a></p>
            </article>
        </div>
    </x-container>
</x-layout>
