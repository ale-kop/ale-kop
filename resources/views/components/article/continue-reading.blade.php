{{-- "Continue lendo": últimos posts avulsos, cada um com a cor do seu tema. --}}
@props(['posts'])

@if($posts->isNotEmpty())
    <section class="article-next" aria-labelledby="article-next-title">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 id="article-next-title">Continue lendo</h2>
            <div class="article-next-grid">
                @foreach($posts as $item)
                    <a href="{{ route('posts.show', $item->slug) }}"
                       style="--topic: {{ $item->tag?->accentColor() ?? \App\Models\Tag::DEFAULT_COLOR }}">
                        @if($item->tag)
                            <span class="article-tag">{{ $item->tag->name }}</span>
                        @endif
                        <h3>{{ $item->name }}</h3>
                        <time datetime="{{ $item->created_at->toDateString() }}">{{ $item->created_at->locale('pt_BR')->translatedFormat('j M Y') }}</time>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
