<x-layout title="Gerenciar Cursos">
    <x-container>
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('admin.index') }}" class="text-sm text-gray-500 hover:text-brand transition-colors">← Admin</a>
                <h1 class="text-3xl font-bold mt-1">Cursos</h1>
            </div>
            <a href="{{ route('courses.create') }}" class="px-3 py-2 rounded-lg bg-brand text-white text-sm font-semibold hover:bg-brand/90 transition-colors">Novo curso</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Nome</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Slug</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Seções</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Posts</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Destaque</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5">
                                    @if($thumb = $course->image('thumb'))
                                        <img src="{{ $thumb }}" alt="{{ $course->name }}" class="w-8 h-8 rounded object-cover shrink-0">
                                    @else
                                        <div class="w-8 h-8 rounded bg-brand/10 flex items-center justify-center shrink-0">
                                            <x-heroicon-o-academic-cap class="w-4 h-4 text-brand"/>
                                        </div>
                                    @endif
                                    <a href="{{ route('courses.show', $course->slug) }}"
                                       class="font-medium text-gray-900 dark:text-gray-100 hover:text-brand transition-colors">
                                        {{ $course->name }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $course->slug }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $course->sections->count() }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $course->posts->count() }}</td>
                            <td class="px-4 py-3">
                                @if(data_get($course->extra, 'featured'))
                                    <span class="inline-flex items-center text-[11px] font-medium px-2 py-0.5 rounded bg-amber-100 text-amber-700">Destaque</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 space-x-2">
                                <a href="{{ route('courses.edit', $course) }}" class="px-2 py-1 rounded border text-xs hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Editar</a>
                                <form action="{{ route('courses.destroy', $course) }}" method="post" class="inline" data-confirm="Deseja realmente apagar este curso?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 rounded border border-red-200 text-red-600 text-xs hover:bg-red-50 transition-colors">Apagar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-4 text-gray-500" colspan="6">Nenhum curso encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-container>
</x-layout>
