@php
$navLinks = [
    ['/', 'Home', request()->is('/')],
    //[route('courses.index'), 'Cursos', request()->routeIs('courses.*')],
];
@endphp


<header ak-top-nav class="fixed bg-transparent w-full z-50 transition-transform duration-300 px-8">
    <div class="sm:px-6 py-6 flex items-center justify-between">

        {{-- Brand --}}
        <a href="/" class="font-serif text-2xl italic font-semibold tracking-tight text-gray-700 dark:text-white shrink-0">
            {{ config('app.name', 'App') }}
        </a>

        <div class="flex items-center gap-3">

            {{-- ── Desktop pill nav ──────────────────────────────── --}}
            <ul class="hidden md:flex items-center rounded-full bg-white/90 px-3 text-sm font-semibold text-zinc-700 shadow-lg ring-1 shadow-zinc-800/5 ring-zinc-900/5 backdrop-blur-sm dark:bg-zinc-800/90 dark:text-zinc-200 dark:ring-white/10">

                @foreach($navLinks as [$href, $label, $isActive])
                    <li>
                        <a href="{{ $href }}"
                           class="relative block px-3 py-2 transition-colors {{ $isActive ? 'text-teal-500 dark:text-teal-400' : 'hover:text-teal-500 dark:hover:text-teal-400' }}">
                            {{ $label }}
                            @if($isActive)
                                <span class="absolute inset-x-1 -bottom-px h-px bg-linear-to-r from-teal-500/0 via-teal-500/40 to-teal-500/0 dark:from-teal-400/0 dark:via-teal-400/40 dark:to-teal-400/0"></span>
                            @endif
                        </a>
                    </li>
                @endforeach

                <li class="relative">
                    <button ak-toggle="topics-dropdown" ak-toggle-classes="hidden" ak-toggle-close-on-blur="true"
                            class="flex items-center gap-1 px-3 py-2 transition-colors hover:text-teal-500 dark:hover:text-teal-400 focus:outline-none">
                        Assuntos <x-heroicon-o-chevron-down class="w-3.5 h-3.5 mt-px"/>
                    </button>
                    <ul id="topics-dropdown"
                        class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg hidden">
                        @forelse(($tagsWithContent ?? []) as $tag)
                            <li>
                                <a href="{{ route('posts.index', $tag->slug) }}"
                                   class="block px-4 py-2 text-sm hover:bg-teal-50 dark:hover:bg-zinc-700 transition-colors first:rounded-t-xl last:rounded-b-xl">
                                    {{ $tag->name }}
                                </a>
                            </li>
                        @empty
                            <li class="px-4 py-2 text-sm text-zinc-400">Nenhuma tag</li>
                        @endforelse
                    </ul>
                </li>

            </ul>

            {{-- ── Mobile controls ──────────────────────────────── --}}
            <div class="flex items-center gap-2 md:hidden">
                <button id="theme-switch-mobile"
                        class="flex items-center p-2 rounded-full bg-white/90 ring-1 ring-zinc-900/5 shadow-md text-zinc-600 dark:text-zinc-300 dark:bg-zinc-800/90 dark:ring-white/10">
                    <x-heroicon-o-moon class="w-5 h-5 dark:hidden"/>
                    <x-heroicon-o-sun class="w-5 h-5 hidden dark:inline"/>
                </button>
                <button data-side-panel-open="#menu-panel" data-side-panel-overlay="#menu-overlay"
                        class="flex items-center p-2 rounded-full bg-white/90 ring-1 ring-zinc-900/5 shadow-md text-zinc-600 dark:text-zinc-300 dark:bg-zinc-800/90 dark:ring-white/10">
                    <x-heroicon-o-bars-3 class="w-5 h-5"/>
                </button>
            </div>

        </div>

        <div class="flex gap-4">

            {{-- ── Avatar (desktop) ──────────────────────────────── --}}
            @auth
                <a href="{{ route('admin.index') }}"
                   class="hidden md:flex w-8 h-8 rounded-full bg-teal-50 dark:bg-zinc-800 items-center justify-center
                          text-[11px] font-bold text-teal-600 dark:text-teal-400 select-none
                          ring-1 ring-teal-200 dark:ring-teal-700
                          hover:ring-2 hover:ring-teal-400/60 transition-all"
                   title="Painel admin">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </a>
            @endauth

            {{-- ── Theme toggle (desktop) ────────────────────────── --}}
            <button id="theme-switch"
                    class="hidden md:inline-flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-2 text-xs font-medium shadow-lg ring-1 shadow-zinc-800/5 ring-zinc-900/5 backdrop-blur-sm dark:bg-zinc-800/90 dark:ring-white/10 dark:hover:ring-white/20 text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 transition">
                <x-heroicon-o-moon class="w-3.5 h-3.5 dark:hidden"/>
                <x-heroicon-o-sun class="w-3.5 h-3.5 hidden dark:inline"/>
                <span class="dark:hidden">Escuro</span>
                <span class="hidden dark:inline">Claro</span>
            </button>
        </div>
    </div>
</header>

{{-- ── Mobile overlay + drawer ──────────────────────────────────── --}}
<div id="menu-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200 z-40 md:hidden"></div>

<aside id="menu-panel" class="fixed top-0 right-0 h-full w-1/2 bg-white dark:bg-gray-800 transform translate-x-full scale-95 transition-transform duration-300 ease-[cubic-bezier(0.22,1,0.36,1)] z-50 p-6 space-y-4 md:hidden opacity-0 transition-opacity shadow-2xl ring-1 ring-black/5">
    <ul class="space-y-2">

        @foreach($navLinks as [$href, $label, $isActive])
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

        {{-- <li>
            <span class="block py-2 font-semibold">Cursos</span>
            <ul class="ml-4 space-y-1 max-h-56 overflow-auto pr-2">
                @foreach(($coursesWithContent ?? []) as $course)
                    <li><a href="{{ route('courses.show', $course->slug) }}" class="block py-1 text-sm">{{ $course->name }}</a></li>
                @endforeach
            </ul>
        </li> --}}

        @auth
            <li><a href="{{ route('admin.index') }}" class="block py-2 text-sm text-gray-500">Admin</a></li>
        @endauth

    </ul>
</aside>
