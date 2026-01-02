@props(['post' => null, 'isRead' => null])
@auth
    @if($isRead)
        <form action="{{ route('posts.unread', $post) }}" method="post" class="inline">
            @csrf
            <x-forms.button type="submit">Desmarcar</x-forms.button>
        </form>
    @else
        <form action="{{ route('posts.read', $post) }}" method="post" class="inline">
            @csrf
            <x-forms.button type="submit">Marcar como lido</x-forms.button>
        </form>
    @endif
@endauth
