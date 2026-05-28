<x-layout title="Newsletter · Listas">
    <x-container>
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Listas de Newsletter</h1>
                <p class="text-sm text-gray-500 mt-0.5">Gerencie os grupos de inscritos</p>
            </div>
            <a href="{{ route('admin.newsletter.lists.create') }}"
               class="px-4 py-2 rounded-lg bg-brand text-white text-sm font-semibold hover:bg-brand/90 transition-colors">
                + Nova Lista
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            @if($lists->isEmpty())
                <div class="px-6 py-12 text-center text-gray-400 text-sm">Nenhuma lista cadastrada ainda.</div>
            @else
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Slug</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Inscritos</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($lists as $list)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                    {{ $list->name }}
                                    @if($list->description)
                                        <p class="text-xs text-gray-400 font-normal mt-0.5">{{ $list->description }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $list->slug }}</td>
                                <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-300 font-semibold">
                                    {{ number_format($list->subscribers_count) }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.newsletter.lists.edit', $list) }}"
                                           class="text-xs text-brand hover:underline">Editar</a>
                                        <form action="{{ route('admin.newsletter.lists.destroy', $list) }}" method="POST"
                                              onsubmit="return confirm('Remover esta lista?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:underline">Remover</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($lists->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                        {{ $lists->links() }}
                    </div>
                @endif
            @endif
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.index') }}" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">← Painel</a>
        </div>
    </x-container>
</x-layout>
