@php /** @var \App\Models\Tag $tag */ @endphp
<x-layout :title="'Editar Tag: ' . $tag->name">
    <x-container class="max-w-2xl">
        <h1 class="text-3xl font-bold mb-6">Editar Tag</h1>
        <form action="{{ route('tags.update', $tag) }}" method="post" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')
            @include('tags.form', ['tag' => $tag])
            <div class="flex gap-3">
                <x-forms.button type="submit">Atualizar</x-forms.button>
                <a href="{{ route('tags.index') }}" class="px-3 py-2 rounded border hover:bg-gray-50 dark:hover:bg-gray-800">Cancelar</a>
            </div>
        </form>
    </x-container>
</x-layout>

