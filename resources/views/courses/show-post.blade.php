<x-layout :title="$post->name . ' — ' . ($post->course->name ?? '')">
    <x-container class="pt-16">
        <div class="flex gap-6">
            <article class="space-y-6 flex-1">
                @if($url = $post->image('large'))
                    <img src="{{ $url }}" alt="{{ $post->name }}" class="w-full rounded-lg">
                @endif
                <x-forms.input-with-button></x-forms.input-with-button>
                <x-forms.input></x-forms.input>
                <x-forms.input-with-button></x-forms.input-with-button>
                <x-forms.input></x-forms.input>
                <x-forms.input-with-button></x-forms.input-with-button>
                <x-forms.input></x-forms.input>
                <x-forms.input-with-button></x-forms.input-with-button>
                <x-forms.input></x-forms.input>
                <x-forms.input-with-button></x-forms.input-with-button>
                <x-forms.input></x-forms.input>
                <x-forms.input-with-button></x-forms.input-with-button>
                <x-forms.input></x-forms.input>
                <a href="{{ route('courses.show', $post->course->slug) }}" class="text-blue-600 hover:underline">← Voltar ao curso</a>
                <h1 class="text-3xl font-bold flex items-center gap-2">
                    {{ $post->name }}
                    <x-mark-read-post-button :post="$post" :is-read="$isRead" />
                </h1>
                @if($url = $post->image('large'))
                    <img src="{{ $url }}" alt="{{ $post->name }}" class="w-full rounded-lg">
                @endif
                <div class="html-content max-w-none">{!! $post->content !!}</div>
            </article>
            <x-post-index :post="$post" />
        </div>
    </x-container>
</x-layout>
