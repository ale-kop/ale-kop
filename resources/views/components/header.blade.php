<header class="bg-white/80 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700 backdrop-blur">
    <nav class="container mx-auto flex items-center justify-between p-4">
        <a href="/" class="text-xl font-bold">{{ config('app.name', 'App') }}</a>

        <ul class="hidden md:flex items-center gap-6">
            <li><a href="/" class="hover:text-blue-600">Home</a></li>
            <li><a href="/cursos" class="hover:text-blue-600">Cursos</a></li>
            <li><a href="/tags" class="hover:text-blue-600">Tags</a></li>

            <li class="relative">
                <button
                    ak-toggle="admin-dropdown"
                    ak-toggle-classes="hidden"
                    ak-toggle-close-on-blur="true"
                    class="flex items-center gap-1 hover:text-blue-600 focus:outline-none"
                >
                    Admin
                    <x-heroicon-o-chevron-down class="w-4 h-4"/>
                </button>
                <ul id="admin-dropdown"
                    class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-lg hidden">
                    <li><a href="{{ route('posts.manage') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Gerenciar posts</a></li>
                    <li><a href="{{ route('posts.create') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Criar post</a></li>
                    <li><a href="{{ route('sections.index') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Gerenciar seções</a></li>
                    <li><a href="{{ route('tags.index') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Gerenciar tags</a></li>
                    <li><a href="{{ route('courses.index') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Gerenciar cursos</a></li>
                </ul>
            </li>

            <li class="relative">
                <button
                    ak-toggle="topics-dropdown"
                    ak-toggle-classes="hidden"
                    ak-toggle-close-on-blur="true"
                    class="flex items-center gap-1 hover:text-blue-600 focus:outline-none"
                >
                    Assuntos
                    <x-heroicon-o-chevron-down class="w-4 h-4"/>
                </button>
                <ul id="topics-dropdown"
                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-lg hidden">
                    @forelse(($tagsWithContent ?? []) as $tag)
                        <li>
                            <a href="{{ route('posts.index', $tag->slug) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">{{ $tag->name }}</a>
                        </li>
                    @empty
                        <li class="px-4 py-2 text-sm text-gray-500">Nenhuma tag</li>
                    @endforelse
                </ul>
            </li>

            <li class="relative">
                <button
                    ak-toggle="courses-dropdown"
                    ak-toggle-classes="hidden"
                    ak-toggle-close-on-blur="true"
                    class="flex items-center gap-1 hover:text-blue-600 focus:outline-none"
                >
                    Cursos
                    <x-heroicon-o-chevron-down class="w-4 h-4"/>
                </button>
                <ul id="courses-dropdown"
                    class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-lg hidden">
                    @forelse(($coursesWithContent ?? []) as $course)
                        <li>
                            <a href="{{ route('courses.show', $course->slug) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">{{ $course->name }}</a>
                        </li>
                    @empty
                        <li class="px-4 py-2 text-sm text-gray-500">Nenhum curso</li>
                    @endforelse
                </ul>
            </li>
        </ul>

        <div class="flex items-center gap-4">
            <button data-side-panel-open="#menu-panel" data-side-panel-overlay="#menu-overlay" class="md:hidden text-gray-600 dark:text-gray-300 focus:outline-none">
                <x-heroicon-o-bars-3 class="w-6 h-6"/>
            </button>
            <button id="theme-switch" class="flex items-center text-gray-600 dark:text-gray-300 focus:outline-none">
                <x-heroicon-o-moon class="w-6 h-6 dark:hidden"/>
                <x-heroicon-o-sun class="w-6 h-6 hidden dark:inline"/>
            </button>
        </div>
    </nav>
</header>

<!-- Mobile overlay + side panel (mantido) -->
<div id="menu-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200 z-40 md:hidden"></div>
<aside id="menu-panel" class="fixed top-0 right-0 h-full w-1/2 bg-white dark:bg-gray-800 transform translate-x-full scale-95 transition-transform duration-300 ease-[cubic-bezier(0.22,1,0.36,1)] z-50 p-6 space-y-4 md:hidden opacity-0 transition-opacity shadow-2xl ring-1 ring-black/5">
    <ul class="space-y-2">
        <li><a href="/" class="block py-2 border-b border-gray-200 dark:border-gray-700">Home</a></li>
        <li><a href="/cursos" class="block py-2 border-b border-gray-200 dark:border-gray-700">Cursos</a></li>
        <li><a href="/tags" class="block py-2 border-b border-gray-200 dark:border-gray-700">Tags</a></li>
        <li>
            <span class="block py-2 font-semibold">Assuntos</span>
            <ul class="ml-4 space-y-1 max-h-56 overflow-auto pr-2">
                @foreach(($tagsWithContent ?? []) as $tag)
                    <li><a href="{{ route('posts.index', $tag->slug) }}" class="block py-1 text-sm">{{ $tag->name }}</a></li>
                @endforeach
            </ul>
        </li>
        <li>
            <span class="block py-2 font-semibold">Cursos</span>
            <ul class="ml-4 space-y-1 max-h-56 overflow-auto pr-2">
                @foreach(($coursesWithContent ?? []) as $course)
                    <li><a href="{{ route('courses.show', $course->slug) }}" class="block py-1 text-sm">{{ $course->name }}</a></li>
                @endforeach
            </ul>
        </li>
    </ul>
</aside>
