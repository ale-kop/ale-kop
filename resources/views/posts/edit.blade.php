@php /** @var \App\Models\Post $post */ @endphp
<x-layout :title="'Editar Post: ' . $post->name">
    <x-container class="pt-16 max-w-2xl">
        <h1 class="text-3xl font-bold mb-6">Editar Post</h1>
        <form action="{{ route('posts.update', $post) }}" method="post" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <div>
                <x-forms.label for="name">Nome</x-forms.label>
                <x-forms.input id="name" name="name" type="text" :value="old('name', $post->name)" required />
            </div>

            <div>
                <x-forms.label for="slug">Slug</x-forms.label>
                <x-forms.input id="slug" name="slug" type="text" :value="old('slug', $post->slug)" />
            </div>

            <div>
                <x-forms.label for="tag_id">Tag</x-forms.label>
                <x-forms.select id="tag_id" name="tag_id">
                    <option value="">Selecione uma tag</option>
                    @foreach(($tags ?? []) as $tag)
                        <option value="{{ $tag->id }}" @selected(old('tag_id', $post->tag_id) == $tag->id)>{{ $tag->name }}</option>
                    @endforeach
                </x-forms.select>
            </div>

            <div>
                <x-forms.label for="section_id">Seção (opcional — define o curso automaticamente)</x-forms.label>
                <x-forms.select id="section_id" name="section_id">
                    <option value="">Selecione a seção</option>
                    @foreach(($sections ?? []) as $section)
                        <option value="{{ $section->id }}" @selected(old('section_id', $post->section_id) == $section->id)>
                            {{ $section->name }} @if($section->course) ({{ $section->course->name }}) @endif
                        </option>
                    @endforeach
                </x-forms.select>
            </div>

            <div>
                <x-forms.label for="content">Conteúdo</x-forms.label>
                <x-forms.rich-text-editor id="content" name="content" :value="old('content', $post->content)" />
            </div>

            <div>
                <x-forms.label for="featured_image">Imagem destacada</x-forms.label>
                <x-forms.file id="featured_image" name="featured_image" accept="image/*" />
            </div>

            <div class="flex items-center gap-2">
                <input id="featured" name="extra[featured]" type="checkbox" value="1" @checked(old('extra.featured', (bool) data_get($post->extra, 'featured'))) />
                <x-forms.label for="featured">Destacar</x-forms.label>
            </div>

            <div class="flex gap-3">
                <x-forms.button type="submit">Atualizar</x-forms.button>
                <a href="{{ route('posts.show', $post->slug) }}" class="px-3 py-2 rounded border hover:bg-gray-50 dark:hover:bg-gray-800">Ver post</a>
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
