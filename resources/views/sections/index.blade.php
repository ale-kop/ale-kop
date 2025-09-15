<x-layout title="Seções">
    <x-container class="pt-16">
        <h1 class="text-4xl font-bold mb-6">Seções</h1>
        <div class="flex justify-end mb-4">
            <a href="{{ route('sections.create') }}" class="px-3 py-1.5 rounded bg-blue-600 text-white hover:bg-blue-700">Nova seção</a>
        </div>

        @if(isset($sections) && $sections->count())
            <div class="grid gap-6 sm:grid-cols-2">
                @foreach($sections as $section)
                    <article class="rounded-lg border border-gray-200 dark:border-gray-800 p-4 space-y-2">
                        <h2 class="text-lg font-semibold">{{ $section->name }}</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Curso: {{ $section->course?->name }}</p>
                        <p class="text-xs text-gray-500">{{ $section->posts_count }} posts</p>
                        <div class="flex gap-2 pt-2">
                            <a href="{{ route('sections.edit', $section) }}" class="px-2 py-1 rounded border hover:bg-gray-50 dark:hover:bg-gray-800">Editar</a>
                            <form action="{{ route('sections.destroy', $section) }}" method="post" onsubmit="return confirm('Apagar seção?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 rounded border border-red-300 text-red-600 hover:bg-red-50">Apagar</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="text-gray-600 dark:text-gray-300">Nenhuma seção encontrada.</p>
        @endif
    </x-container>
</x-layout>

