<x-layout title="Nova Tag">
    <x-container class="max-w-2xl">
        <h1 class="text-3xl font-bold mb-6">Nova Tag</h1>
        <form action="{{ route('tags.store') }}" method="post" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('tags.form')
            <div class="flex gap-3">
                <x-forms.button state="loading" type="submit">Salvar</x-forms.button>
                <a href="{{ route('tags.index') }}" class="px-3 py-2 rounded border hover:bg-gray-50 dark:hover:bg-gray-800">Cancelar</a>
            </div>
        </form>
    </x-container>

</x-layout>