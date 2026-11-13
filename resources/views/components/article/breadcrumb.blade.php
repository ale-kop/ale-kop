{{--
    Trilha do post em uma linha: "Artigos › Assunto" (ou "Cursos › Curso").
    O último item usa a cor do tema (--topic-ink), sem ponto nem etiqueta.
    $items: lista de ['label' => string, 'url' => ?string].
--}}
@props(['items'])

<nav class="article-crumbs" aria-label="Você está em">
    <ol>
        @foreach($items as $item)
            <li @class(['is-current' => $loop->last])>
                @if($item['url'] ?? null)
                    <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                @else
                    <span>{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
