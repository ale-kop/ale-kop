@props(['post', 'recentPosts' => collect()])
@php
    $hasCourse = (bool) ($post->course ?? null);
    $panelId   = $hasCourse ? 'post-index-panel-course' : 'post-index-panel-recent';
    $overlayId = $hasCourse ? 'post-index-overlay-course' : 'post-index-overlay-recent';
@endphp

@if($hasCourse)
    <aside class="hidden lg:block w-72 pr-6 sticky top-20 h-[calc(100vh-6rem)] overflow-y-auto">
        <nav class="space-y-4">
            @foreach($post->course->sections as $section)
                <div>
                    <p class="font-semibold text-sm text-gray-700 dark:text-gray-200 mb-2">{{ $section->name }}</p>
                    <ul class="space-y-1">
                        @foreach($section->posts as $sectionPost)
                            @php $active = $post->slug === $sectionPost->slug; @endphp
                            <li class="flex items-center justify-between">
                                <a href="{{ route('courses.showPost', [$post->course->slug, $sectionPost->slug]) }}"
                                   @if($active) data-active @endif
                                   class="block rounded px-2 py-1 text-sm {{ $active ? 'bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-medium' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                                    {{ $sectionPost->name }}
                                </a>
                                @php $isRead = $sectionPost->is_read ?? $sectionPost->isReadBy(auth()->user()); @endphp
                                <span class="inline-flex items-center text-[10px] font-medium px-1.5 py-0.5 rounded {{ $isRead ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $isRead ? 'Lido' : 'Não lido' }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>
    </aside>

    <!-- Mobile floating trigger -->
    <button
        type="button"
        data-side-panel-open="#{{ $panelId }}" data-side-panel-overlay="#{{ $overlayId }}"
        class="lg:hidden fixed left-4 top-20 z-40 inline-flex items-center gap-2 px-3 py-2 rounded border bg-white/90 dark:bg-gray-800/90 shadow-md"
    >
        Abrir índice
    </button>

    <!-- Mobile overlay -->
    <div id="{{ $overlayId }}" class="lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200 z-40"></div>

    <!-- Mobile drawer panel -->
    <aside id="{{ $panelId }}" class="fixed top-0 left-0 h-full w-4/5 max-w-xs bg-white dark:bg-gray-900 transform -translate-x-full scale-95 transition-transform duration-300 ease-[cubic-bezier(0.22,1,0.36,1)] z-50 p-4 opacity-0 transition-opacity shadow-2xl ring-1 ring-black/5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold">Índice do curso</h2>
            <button type="button" data-side-panel-close data-side-panel-target="#{{ $panelId }}" data-side-panel-overlay="#{{ $overlayId }}" class="px-2 py-1 rounded border">Fechar</button>
        </div>
        <nav class="space-y-4">
            @foreach($post->course->sections as $section)
                <div>
                    <p class="font-semibold text-sm text-gray-700 dark:text-gray-200 mb-2">{{ $section->name }}</p>
                    <ul class="space-y-1">
                        @foreach($section->posts as $sectionPost)
                            @php $active = $post->slug === $sectionPost->slug; @endphp
                            <li class="flex items-center justify-between">
                                <a href="{{ route('courses.showPost', [$post->course->slug, $sectionPost->slug]) }}"
                                   @if($active) data-active @endif
                                   class="block rounded px-2 py-1 text-sm {{ $active ? 'bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-medium' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                                    {{ $sectionPost->name }}
                                </a>
                                @php $isRead = $sectionPost->is_read ?? $sectionPost->isReadBy(auth()->user()); @endphp
                                <span class="inline-flex items-center text-[10px] font-medium px-1.5 py-0.5 rounded {{ $isRead ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $isRead ? 'Lido' : 'Não lido' }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>
    </aside>
@else
    <aside class="hidden lg:block w-72 pr-6 sticky top-20 h-[calc(100vh-6rem)] overflow-y-auto">
        <nav class="space-y-2">
            <p class="font-semibold text-sm text-gray-700 dark:text-gray-200 mb-2">Últimos posts</p>
            <ul class="space-y-1">
                @foreach($recentPosts as $p)
                    @php $active = ($post->id === $p->id); @endphp
                    <li class="flex items-center justify-between">
                        <a href="{{ route('posts.show', $p->slug) }}"
                           @if($active) data-active @endif
                           class="block rounded px-2 py-1 text-sm {{ $active ? 'bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-medium' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                            {{ $p->name }}
                        </a>
                        <span class="inline-flex items-center text-[10px] font-medium px-1.5 py-0.5 rounded bg-gray-50 text-gray-400">Avulso</span>
                    </li>
                @endforeach
            </ul>
        </nav>
    </aside>

    <!-- Mobile floating trigger -->
    <button
        type="button"
        data-side-panel-open="#{{ $panelId }}" data-side-panel-overlay="#{{ $overlayId }}"
        class="lg:hidden fixed left-4 top-20 z-40 inline-flex items-center gap-2 px-3 py-2 rounded border bg-white/90 dark:bg-gray-800/90 shadow-md"
    >
        Abrir índice
    </button>

    <!-- Mobile overlay -->
    <div id="{{ $overlayId }}" class="lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200 z-40"></div>

    <!-- Mobile drawer panel -->
    <aside id="{{ $panelId }}" class="fixed top-0 left-0 h-full w-4/5 max-w-xs bg-white dark:bg-gray-900 transform -translate-x-full scale-95 transition-transform duration-300 ease-[cubic-bezier(0.22,1,0.36,1)] z-50 p-4 opacity-0 transition-opacity shadow-2xl ring-1 ring-black/5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold">Últimos posts</h2>
            <button type="button" data-side-panel-close data-side-panel-target="#{{ $panelId }}" data-side-panel-overlay="#{{ $overlayId }}" class="px-2 py-1 rounded border">Fechar</button>
        </div>
        <nav class="space-y-2">
            <ul class="space-y-1">
                @foreach($recentPosts as $p)
                    @php $active = ($post->id === $p->id); @endphp
                    <li class="flex items-center justify-between">
                        <a href="{{ route('posts.show', $p->slug) }}"
                           @if($active) data-active @endif
                           class="block rounded px-2 py-1 text-sm {{ $active ? 'bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-medium' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                            {{ $p->name }}
                        </a>
                        <span class="inline-flex items-center text-[10px] font-medium px-1.5 py-0.5 rounded bg-gray-50 text-gray-400">Avulso</span>
                    </li>
                @endforeach
            </ul>
        </nav>
    </aside>
@endif

