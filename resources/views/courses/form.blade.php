@props(['course' => null])

<div class="space-y-4">
    <div>
        <x-forms.label for="name">Nome</x-forms.label>
        <x-forms.input id="name" name="name" type="text" :value="old('name', optional($course)->name)" required />
    </div>

    <div>
        <x-forms.label for="slug">Slug</x-forms.label>
        <x-forms.input id="slug" name="slug" type="text" :value="old('slug', optional($course)->slug)" />
    </div>

    <div>
        <x-forms.label for="meta_description">Descrição</x-forms.label>
        <x-forms.textarea id="meta_description" name="meta[description]" rows="3">{{ old('meta.description', data_get($course?->meta, 'description')) }}</x-forms.textarea>
    </div>

    <div class="flex items-center gap-2">
        <input id="featured" name="extra[featured]" type="checkbox" value="1" @checked(old('extra.featured', (bool) data_get($course?->extra, 'featured'))) />
        <x-forms.label for="featured">Destacar</x-forms.label>
    </div>

    <div>
        <x-forms.label for="custom_url">URL personalizada</x-forms.label>
        <x-forms.input id="custom_url" name="extra[custom_url]" type="text" :value="old('extra.custom_url', data_get($course?->extra, 'custom_url'))" />
    </div>

    <div>
        <x-forms.label for="featured_image">Imagem</x-forms.label>
        <x-forms.file id="featured_image" name="featured_image" accept="image/*" />
    </div>

    @if($errors->any())
        <ul class="text-sm text-red-600">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</div>

