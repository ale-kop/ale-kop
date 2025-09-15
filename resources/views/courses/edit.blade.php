@php /** @var \App\Models\Course $course */ @endphp
<x-layout :title="'Editar Curso: ' . $course->name">
    <x-container class="pt-16 max-w-2xl">
        <h1 class="text-3xl font-bold mb-6">Editar Curso</h1>
        <form action="{{ route('courses.update', $course) }}" method="post" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')
            @include('courses.form', ['course' => $course])
            <div class="flex gap-3">
                <x-forms.button type="submit">Atualizar</x-forms.button>
                <a href="{{ route('courses.index') }}" class="px-3 py-2 rounded border hover:bg-gray-50 dark:hover:bg-gray-800">Cancelar</a>
            </div>
        </form>
    </x-container>
</x-layout>

