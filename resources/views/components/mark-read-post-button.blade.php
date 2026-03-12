@props(['post' => null, 'isRead' => null])
@auth
    @if($isRead)
        <form action="{{ route('posts.unread', $post) }}" method="post" class="inline">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600
                       text-sm font-medium text-gray-600 dark:text-gray-300
                       bg-white dark:bg-gray-800
                       hover:border-gray-400 hover:text-gray-800 dark:hover:text-gray-100
                       transition-colors cursor-pointer">
                <x-heroicon-o-arrow-uturn-left class="w-4 h-4"/>
                Desmarcar como lido
            </button>
        </form>
    @else
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <form action="{{ route('posts.read', $post) }}" method="post" class="inline">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-lg
                           text-sm font-semibold text-white
                           bg-done hover:bg-green-800
                           transition-colors cursor-pointer shadow-sm">
                    <x-heroicon-s-check class="w-4 h-4"/>
                    Marcar aula como concluída
                </button>
            </form>
            <span class="text-xs text-gray-400 dark:text-gray-500">
                Avança o seu progresso no curso
            </span>
        </div>
    @endif
@endauth
