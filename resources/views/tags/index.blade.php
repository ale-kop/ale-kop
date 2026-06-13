<x-layout title="Gerenciar Tags">
    <x-container>
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('admin.index') }}" class="text-sm text-gray-500 hover:text-brand transition-colors">← Admin</a>
                <h1 class="text-3xl font-bold mt-1">Tags</h1>
            </div>
            <a href="{{ route('tags.create') }}" class="px-3 py-2 rounded-lg bg-brand text-white text-sm font-semibold hover:bg-brand/90 transition-colors">Nova tag</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Nome</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Slug</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Destaque</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Posts</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tags as $tag)
                        <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $tag->name }}</td>
                            <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $tag->slug }}</td>
                            <td class="px-4 py-3">
                                @if(data_get($tag->extra, 'featured'))
                                    <span class="inline-flex items-center text-[11px] font-medium px-2 py-0.5 rounded bg-amber-100 text-amber-700">Destaque</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $tag->posts_count }}</td>
                            <td class="px-4 py-3 space-x-2">
                                <a href="{{ route('posts.index', $tag->slug) }}" class="px-2 py-1 rounded border text-xs hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Ver posts</a>
                                <a href="{{ route('tags.edit', $tag) }}" class="px-2 py-1 rounded border text-xs hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Editar</a>
                                <form action="{{ route('tags.destroy', $tag) }}" method="post" class="inline" onsubmit="return confirm('Apagar tag?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 rounded border border-red-200 text-red-600 text-xs hover:bg-red-50 transition-colors">Apagar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-4 text-gray-500" colspan="5">Nenhuma tag encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-container>
</x-layout>
