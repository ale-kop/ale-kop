<x-layout title="Novo Post">
    <x-container class="pt-16 max-w-2xl">
        <h1 class="text-3xl font-bold mb-6">Novo Post</h1>
        <form action="{{ route('posts.store') }}" method="post" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <x-forms.label for="name">Nome</x-forms.label>
                <x-forms.input id="name" name="name" type="text" :value="old('name')" required />
            </div>

            <div>
                <x-forms.label for="slug">Slug</x-forms.label>
                <x-forms.input id="slug" name="slug" type="text" :value="old('slug')" />
            </div>

            <div>
                <x-forms.label for="tag_id">Tag</x-forms.label>
                <x-forms.select id="tag_id" name="tag_id">
                    <option value="">Selecione uma tag</option>
                    @foreach(($tags ?? []) as $tag)
                        <option value="{{ $tag->id }}" @selected(old('tag_id') == $tag->id)>{{ $tag->name }}</option>
                    @endforeach
                </x-forms.select>
            </div>

            <div>
                <x-forms.label for="section_id">Seção (opcional — define o curso automaticamente)</x-forms.label>
                <x-forms.select id="section_id" name="section_id">
                    <option value="">Selecione a seção</option>
                    @foreach(($sections ?? []) as $section)
                        <option value="{{ $section->id }}" @selected(old('section_id') == $section->id)>
                            {{ $section->name }} @if($section->course) ({{ $section->course->name }}) @endif
                        </option>
                    @endforeach
                </x-forms.select>
            </div>

            <div>
                <x-forms.label for="content">Conteúdo</x-forms.label>
                <x-forms.rich-text-editor id="content" name="content" :value="old('content')" />
            </div>

            <div>
                <x-forms.label for="featured_image">Imagem destacada</x-forms.label>
                <x-forms.file id="featured_image" name="featured_image" accept="image/*" />
            </div>

            <div class="flex items-center gap-2">
                <input id="featured" name="extra[featured]" type="checkbox" value="1" @checked(old('extra.featured')) />
                <x-forms.label for="featured">Destacar</x-forms.label>
            </div>

            <div class="flex gap-3">
                <x-forms.button type="submit">Salvar</x-forms.button>
                <a href="{{ url()->previous() }}" class="px-3 py-2 rounded border hover:bg-gray-50 dark:hover:bg-gray-800">Cancelar</a>
            </div>

            @if($errors->any())
                <ul class="text-sm text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </form>
    </x-container>
</x-layout>
