<x-layout :title="$course->name">
    <x-container class="pt-16">
        <h1 class="text-4xl font-bold mb-6">{{ $course->name }}</h1>
    </x-container>

    @if($url = $course->image('large'))
        <div class="w-full relative">
            <div class="absolute w-full h-full bg-linear-to-r via-50% via-black to-black"></div>
            <div class="absolute w-full h-full bg-black/5"></div>
            <img src="{{ $url }}" alt="{{ $course->name }}" class="w-1/2 mb-6 h-60 object-cover">
        </div>
    @endif

    <x-container class="pt-16">
        <p class="text-gray-600 dark:text-gray-300 mb-10">{{ data_get($course->meta, 'description') }}</p>

        <h2 class="text-2xl font-semibold mb-4">Posts</h2>
        @if($course->posts && $course->posts->count())
            <x-posts-list route="courses.showPost" :posts="$course->posts" />
        @else
            <p class="text-gray-600 dark:text-gray-300">Este curso ainda não possui posts.</p>
        @endif

        <p class="mt-8"><a href="{{ route('courses.index') }}" class="text-blue-600 hover:underline">Voltar aos cursos</a></p>
    </x-container>
</x-layout>
