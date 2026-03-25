<x-layout title="Gerenciar Seções">
    <x-container>
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('admin.index') }}" class="text-sm text-gray-500 hover:text-brand transition-colors">← Admin</a>
                <h1 class="text-3xl font-bold mt-1">Seções</h1>
            </div>
            <a href="{{ route('sections.create') }}" class="px-3 py-2 rounded-lg bg-brand text-white text-sm font-semibold hover:bg-brand/90 transition-colors">Nova seção</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Nome</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Curso</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Posts</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $section)
                        <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $section->name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $section->course?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $section->posts_count }}</td>
                            <td class="px-4 py-3 space-x-2">
                                <a href="{{ route('sections.edit', $section) }}" class="px-2 py-1 rounded border text-xs hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Editar</a>
                                <form action="{{ route('sections.destroy', $section) }}" method="post" class="inline" onsubmit="return confirm('Apagar seção?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 rounded border border-red-200 text-red-600 text-xs hover:bg-red-50 transition-colors">Apagar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-4 text-gray-500" colspan="4">Nenhuma seção encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-container>
</x-layout>
