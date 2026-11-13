{{-- Cabeçalho de post no padrão "post-b": título (sans), linha fina (serif) e autor. A trilha fica em <x-article.breadcrumb>. --}}
@props(['post', 'shareable' => true])

@php
    $dek = data_get($post->meta, 'description');
    $date = $post->created_at?->locale('pt_BR');
@endphp

<header class="article-head">
    <h1 class="article-title">{{ $post->name }}</h1>

    @if($dek)
        <p class="article-dek">{{ $dek }}</p>
    @endif

    <div class="article-byline">
        <img src="{{ asset('img/ale-kop-avatar.png') }}" alt="" width="40" height="40">
        <div>
            <strong>{{ $post->user?->name ?? 'Aleksandr Kopelevitch' }}</strong>
            <span>
                @if($date)
                    <time datetime="{{ $date->toDateString() }}">{{ $date->translatedFormat('j \d\e F \d\e Y') }}</time>,
                @endif
                {{ $post->readingMinutes() }} min de leitura
            </span>
        </div>
        @if($shareable)
            <button type="button" class="article-btn article-btn-ghost article-share" data-copy-link>Copiar link</button>
        @endif
    </div>
</header>
