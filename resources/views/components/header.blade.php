@php
$navLinks = [
    ['/', 'Home'],
    [route('courses.index'), 'Cursos'],
];
@endphp

<header ak-top-nav class="bg-white/90 dark:bg-gray-800/90 justify-between backdrop-blur-lg fixed w-full z-50 p-4 py-4 border-b border-gray-200/50 dark:border-gray-700/60">
    <nav class="max-w-7xl mx-auto flex items-center justify-between">

        {{-- Brand --}}
        <a href="/" class="text-2xl font-bold text-gray-700 dark:text-brand-light">
            {{ config('app.name', 'App') }}
        </a>

        <div class="flex space-x-10">

            {{-- ── Desktop nav ──────────────────────────────────────────── --}}
            <ul class="hidden md:flex items-center gap-6 text-gray-500 dark:text-gray-300 font-semibold text-base">

                @foreach($navLinks as [$href, $label])
                    <li><a href="{{ $href }}" class="hover:text-brand dark:hover:text-brand-light transition-colors">{{ $label }}</a></li>
                @endforeach

                <li class="relative">
                    <button ak-toggle="topics-dropdown" ak-toggle-classes="hidden" ak-toggle-close-on-blur="true"
                            class="flex items-center gap-1 hover:text-brand dark:hover:text-brand-light transition-colors focus:outline-none">
                        Assuntos <x-heroicon-o-chevron-down class="w-4 h-4"/>
                    </button>
                    <ul id="topics-dropdown"
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg hidden">
                        @forelse(($tagsWithContent ?? []) as $tag)
                            <li><a href="{{ route('posts.index', $tag->slug) }}"
                                   class="block px-4 py-2 hover:bg-brand-light dark:hover:bg-gray-700 text-sm">{{ $tag->name }}</a></li>
                        @empty
                            <li class="px-4 py-2 text-sm text-gray-500">Nenhuma tag</li>
                        @endforelse
                    </ul>
                </li>

            </ul>

            {{-- ── Ações direitas ──────────────────────────────────────── --}}
            <div class="flex items-center gap-3">

                {{-- Botão tema desktop --}}
                <button id="theme-switch"
                        class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700
                               text-xs font-medium text-gray-500 dark:text-gray-400
                               hover:border-brand hover:text-brand dark:hover:border-indigo-500 dark:hover:text-indigo-300
                               transition-colors focus:outline-none">
                    <x-heroicon-o-moon class="w-3.5 h-3.5 dark:hidden"/>
                    <x-heroicon-o-sun class="w-3.5 h-3.5 hidden dark:inline"/>
                    <span class="dark:hidden">Escuro</span>
                    <span class="hidden dark:inline">Claro</span>
                </button>

                {{-- Avatar --}}
                @auth
                    <a href="{{ route('admin.index') }}"
                       class="hidden md:flex w-8 h-8 rounded-full bg-brand-light dark:bg-indigo-900/50 items-center justify-center
                              text-[11px] font-bold text-brand dark:text-indigo-300 select-none
                              hover:ring-2 hover:ring-brand/30 transition-all"
                       title="Painel admin">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                    </a>
                @endauth

                {{-- Hamburger mobile --}}
                <button data-side-panel-open="#menu-panel" data-side-panel-overlay="#menu-overlay"
                        class="md:hidden text-gray-600 dark:text-gray-300 focus:outline-none">
                    <x-heroicon-o-bars-3 class="w-6 h-6"/>
                </button>

                {{-- Botão tema mobile --}}
                <button id="theme-switch-mobile"
                        class="md:hidden flex items-center text-gray-600 dark:text-gray-300 focus:outline-none">
                    <x-heroicon-o-moon class="w-5 h-5 dark:hidden"/>
                    <x-heroicon-o-sun class="w-5 h-5 hidden dark:inline"/>
                </button>

            </div>
        </div>
    </nav>
</header>

{{-- ── Mobile overlay + drawer ────────────────────────────────────── --}}
<div id="menu-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200 z-40 md:hidden"></div>

<aside id="menu-panel" class="fixed top-0 right-0 h-full w-1/2 bg-white dark:bg-gray-800 transform translate-x-full scale-95 transition-transform duration-300 ease-[cubic-bezier(0.22,1,0.36,1)] z-50 p-6 space-y-4 md:hidden opacity-0 transition-opacity shadow-2xl ring-1 ring-black/5">
    <ul class="space-y-2">

        @foreach($navLinks as [$href, $label])
            <li><a href="{{ $href }}" class="block py-2 border-b border-gray-200 dark:border-gray-700">{{ $label }}</a></li>
        @endforeach

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

        @auth
            <li><a href="{{ route('admin.index') }}" class="block py-2 text-sm text-gray-500">Admin</a></li>
        @endauth

    </ul>
</aside>
