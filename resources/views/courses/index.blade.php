<x-layout title="Cursos">
    <x-container class="pt-16">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-4xl font-bold">Cursos</h1>
            <a href="{{ route('courses.create') }}" class="px-3 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Novo Curso</a>
        </div>
        @if(isset($courses) && $courses->count())
            <div class="grid gap-6 sm:grid-cols-2">
                @foreach($courses as $course)
                    <article class="rounded-lg border border-gray-200 dark:border-gray-800 overflow-hidden">
                        @if($url = $course->image('thumb'))
                            <img src="{{ $url }}" alt="{{ $course->name }}" class="w-full h-40 object-cover">
                        @endif
                        <div class="p-4 space-y-2">
                            <h2 class="text-lg font-semibold">
                                <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-blue-600">{{ $course->name }}</a>
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ data_get($course->meta, 'description') }}</p>
                        </div>
                        <div class="p-4 flex gap-2">
                            <a href="{{ route('courses.edit', $course) }}" class="px-2 py-1 rounded border hover:bg-gray-50 dark:hover:bg-gray-800">Editar</a>
                            <form action="{{ route('courses.destroy', $course) }}" method="post" data-confirm="Deseja realmente apagar este curso?">
                                @csrf
                                @method('DELETE')
                                <x-forms.button type="submit">Apagar</x-forms.button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="text-gray-600 dark:text-gray-300">Nenhum curso encontrado.</p>
        @endif
    </x-container>
</x-layout>
