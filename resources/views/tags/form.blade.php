@props(['tag' => null])

<div class="space-y-4">
    <div>
        <x-forms.label for="name">Nome</x-forms.label>
        <x-forms.input id="name" name="name" type="text" :value="old('name', optional($tag)->name)" required/>
    </div>

    <div>
        <x-forms.label for="slug">Slug</x-forms.label>
        <x-forms.input id="slug" name="slug" type="text" :value="old('slug', optional($tag)->slug)"/>
    </div>

    <div>
        <x-forms.label for="meta_description">Descrição</x-forms.label>
        <x-forms.textarea rows="10" id="meta_description" name="meta[description]">{{ old('meta.description', data_get($tag?->meta, 'description')) }}</x-forms.textarea>
    </div>


    <div class="flex items-center gap-2">
        <input id="featured" name="extra[featured]" type="checkbox"
               value="1" @checked(old('extra.featured', (bool) data_get($tag?->extra, 'featured'))) />
        <x-forms.label for="featured">Destacar</x-forms.label>
    </div>

    <div>
        <x-forms.label for="custom_url">URL personalizada</x-forms.label>
        <x-forms.input icon-left="heroicon-c-envelope" id="custom_url" name="extra[custom_url]" type="text"
                       :value="old('extra.custom_url', data_get($tag?->extra, 'custom_url'))"/>
    </div>

    <div>
        <x-forms.label for="featured_image">Imagem</x-forms.label>
        <x-forms.file id="featured_image" name="featured_image" accept="image/*" :initial-url="$tag?->image('large')" />
    </div>

    <div class="flex items-center justify-between">
        <a href="{{ url('/forgot-password') }}" class="text-sm font-semibold text-gray-900">Forgot your password?</a>
        <x-forms.input-with-button type="password"></x-forms.input-with-button>
    </div>

    @if($errors->any())
        <ul class="text-sm text-red-600">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</div>
