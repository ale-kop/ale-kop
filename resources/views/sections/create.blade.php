<x-layout title="Nova Seção">
    <x-container class="pt-16 max-w-2xl">
        <h1 class="text-3xl font-bold mb-6">Nova Seção</h1>
        <form action="{{ route('sections.store') }}" method="post" class="space-y-6">
            @csrf
            @include('sections.form', ['courses' => $courses])
            <div class="flex gap-3">
                <x-forms.button type="submit">Salvar</x-forms.button>
                <a href="{{ route('sections.index') }}" class="px-3 py-2 rounded border hover:bg-gray-50 dark:hover:bg-gray-800">Cancelar</a>
            </div>
        </form>
    </x-container>
</x-layout>

