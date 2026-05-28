<x-layout title="Editar Lista · Newsletter">
    <x-container class="max-w-xl">
        <h1 class="text-2xl font-bold mb-1">Editar Lista</h1>
        <p class="text-sm text-gray-500 mb-6">{{ number_format($list->subscribers_count) }} inscritos nesta lista</p>

        <form action="{{ route('admin.newsletter.lists.update', $list) }}" method="POST" class="space-y-5">
            @csrf @method('PATCH')

            <div>
                <x-forms.label for="name">Nome</x-forms.label>
                <x-forms.input id="name" name="name" type="text" :value="old('name', $list->name)" required />
            </div>

            <div>
                <x-forms.label for="slug">Slug</x-forms.label>
                <x-forms.input id="slug" name="slug" type="text" :value="old('slug', $list->slug)" />
            </div>

            <div>
                <x-forms.label for="description">Descrição (opcional)</x-forms.label>
                <x-forms.textarea id="description" name="description" rows="2">{{ old('description', $list->description) }}</x-forms.textarea>
            </div>

            @if($errors->any())
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            @endif

            <div class="flex gap-3">
                <x-forms.button type="submit">Salvar alterações</x-forms.button>
                <a href="{{ route('admin.newsletter.lists.index') }}"
                   class="px-3 py-2 rounded border hover:bg-gray-50 dark:hover:bg-gray-800 text-sm">Cancelar</a>
            </div>
        </form>
    </x-container>
</x-layout>
