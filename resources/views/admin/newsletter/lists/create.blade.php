<x-layout title="Nova Lista · Newsletter">
    <x-container class="max-w-xl">
        <h1 class="text-2xl font-bold mb-6">Nova Lista de Newsletter</h1>

        <form action="{{ route('admin.newsletter.lists.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <x-forms.label for="name">Nome</x-forms.label>
                <x-forms.input id="name" name="name" type="text" :value="old('name')" required />
            </div>

            <div>
                <x-forms.label for="slug">Slug</x-forms.label>
                <x-forms.input id="slug" name="slug" type="text" :value="old('slug')"
                               placeholder="gerado-automaticamente" />
                <p class="text-xs text-gray-400 mt-1">Usado na URL de inscrição e como identificador.</p>
            </div>

            <div>
                <x-forms.label for="description">Descrição (opcional)</x-forms.label>
                <x-forms.textarea id="description" name="description" rows="2">{{ old('description') }}</x-forms.textarea>
            </div>

            @if($errors->any())
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            @endif

            <div class="flex gap-3">
                <x-forms.button type="submit">Salvar</x-forms.button>
                <a href="{{ route('admin.newsletter.lists.index') }}"
                   class="px-3 py-2 rounded border hover:bg-gray-50 dark:hover:bg-gray-800 text-sm">Cancelar</a>
            </div>
        </form>
    </x-container>
</x-layout>
