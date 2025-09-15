<x-layout :title="$post->name . ' — ' . ($post->course->name ?? '')">
    <x-container class="pt-16">
        <div class="flex gap-6">
            <x-post-index :post="$post" />
            <article class="space-y-6 flex-1">
                <a href="{{ route('courses.show', $post->course->slug) }}" class="text-blue-600 hover:underline">← Voltar ao curso</a>
                <h1 class="text-3xl font-bold flex items-center gap-2">
                    {{ $post->name }}
                    @auth
                        @php $isRead = $post->is_read ?? $post->isReadBy(auth()->user()); @endphp
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
                </h1>
                @if($url = $post->image('large'))
                    <img src="{{ $url }}" alt="{{ $post->name }}" class="w-full rounded-lg">
                @endif
                <div class="html-content max-w-none">{!! $post->content !!}</div>
            </article>
        </div>
    </x-container>
</x-layout>
