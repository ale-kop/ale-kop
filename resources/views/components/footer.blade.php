<footer class="bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 mt-12">
    <div class="container mx-auto px-4 py-10">
        <nav aria-label="Footer" class="flex flex-wrap gap-4 justify-center">
            @forelse(($tagsWithContent ?? []) as $tag)
                @if (data_get($tag->extra, 'featured'))
                    <a href="{{ data_get($tag->extra, 'custom_url') ?: route('posts.index', $tag->slug) }}" class="text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">{{ $tag->name }}</a>
                @endif
            @empty
            @endforelse
        </nav>
        <p class="mt-6 text-center text-xs text-gray-500">&copy; {{ date('Y') }} {{ config('app.name', 'App') }}</p>
    </div>
</footer>

