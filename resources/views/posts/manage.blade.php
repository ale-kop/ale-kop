<x-layout title="Gerenciar Posts">
    <x-container class="pt-16">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">Gerenciar Posts</h1>
            <a href="{{ route('posts.create') }}" class="px-3 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Novo Post</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-4 py-2 text-left">Título</th>
                        <th class="px-4 py-2 text-left">Tag</th>
                        <th class="px-4 py-2 text-left">Curso</th>
                        <th class="px-4 py-2 text-left">Seção</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr class="border-t border-gray-200 dark:border-gray-800">
                            <td class="px-4 py-2">
                                <a href="{{ route('posts.show', $post->slug) }}" class="hover:underline">{{ $post->name }}</a>
                            </td>
                            <td class="px-4 py-2">{{ $post->tag?->name }}</td>
                            <td class="px-4 py-2">{{ $post->course?->name }}</td>
                            <td class="px-4 py-2">{{ $post->section?->name }}</td>
                            <td class="px-4 py-2">
                                @php
                                    $readable = $post->course_id !== null;
                                    $isRead = $readable ? $post->isReadBy(auth()->user()) : false;
                                @endphp
                                <span class="inline-flex items-center text-[11px] font-medium px-2 py-0.5 rounded {{ $isRead ? 'bg-emerald-100 text-emerald-700' : ($readable ? 'bg-gray-100 text-gray-600' : 'bg-gray-50 text-gray-400') }}">
                                    {{ $isRead ? 'Lido' : ($readable ? 'Não lido' : 'Avulso') }}
                                </span>
                            </td>
                            <td class="px-4 py-2 space-x-2">
                                <a href="{{ route('posts.edit', $post) }}" class="px-2 py-1 rounded border hover:bg-gray-50 dark:hover:bg-gray-800">Editar</a>
                                <form action="{{ route('posts.destroy', $post) }}" method="post" class="inline" onsubmit="return confirm('Apagar Post?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-forms.button type="submit">Apagar</x-forms.button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-2" colspan="5">Nenhum post encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </x-container>
</x-layout>
