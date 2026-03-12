@props(['post'])
@php
    $allPosts  = $post->course->sections->flatMap(fn ($s) => $s->posts);
    $total     = $allPosts->count();
    $readCount = $allPosts->filter(fn ($p) => (bool) ($p->is_read ?? false))->count();
    $progress  = $total > 0 ? round(($readCount / $total) * 100) : 0;
    $panelId   = 'post-index-panel';
    $overlayId = 'post-index-overlay';
    ob_start();
@endphp

{{-- ── Lista de aulas (reutilizada em desktop e mobile) ─── --}}
<div class="space-y-5">
    @foreach($post->course->sections as $section)
        <div class="module-section">

            @if($post->course->sections->count() > 1)
                <p class="text-[0.68rem] font-extrabold text-gray-500 dark:text-gray-400 uppercase tracking-[0.1em]
                           pb-2 mb-1.5 px-1 border-b border-stone-200 dark:border-gray-700">
                    {{ $section->name }}
                </p>
            @endif

            <ul>
                @foreach($section->posts as $sectionPost)
                    @php
                        $active = $post->slug === $sectionPost->slug;
                        $isRead = (bool) ($sectionPost->is_read ?? false);
                    @endphp
                    <li>
                        <a href="{{ route('posts.show', $sectionPost->slug) }}"
                           class="flex items-start gap-2.5 px-1.5 py-2 rounded-md text-sm transition-colors
                               {{ $active
                                   ? 'bg-brand-light dark:bg-indigo-950/60 text-brand dark:text-indigo-300 font-semibold'
                                   : ($isRead
                                       ? 'text-gray-400 dark:text-gray-500 hover:bg-white dark:hover:bg-gray-800'
                                       : 'text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800') }}">

                            {{-- Read state indicator --}}
                            <span class="mt-0.5 w-4 h-4 shrink-0 rounded-full border-2 flex items-center justify-center relative
                                {{ $active && !$isRead
                                    ? 'border-brand dark:border-indigo-500'
                                    : ($isRead
                                        ? 'bg-done border-done dark:bg-emerald-600 dark:border-emerald-600'
                                        : 'bg-white dark:bg-gray-900 border-gray-300 dark:border-gray-600') }}">
                                @if($isRead)
                                    <x-heroicon-s-check class="w-2 h-2 text-white" />
                                @endif
                            </span>

                            <span class="leading-snug">{{ $sectionPost->name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>

@php $navHtml = ob_get_clean(); @endphp

{{-- ─── Desktop sidebar — fiel ao mockup ─────────────────────────── --}}
<aside class="hidden lg:block shrink-0 bg-cream dark:bg-gray-900/50
               sticky top-20 h-[calc(100vh-5rem)] overflow-y-auto
               px-5 py-7">

    {{-- Card de progresso --}}
    <div class="bg-white dark:bg-gray-800 border border-stone-200 dark:border-stone-700 rounded-xl p-4 mb-5">
        <h3 class="text-[0.68rem] font-extrabold text-gray-500 dark:text-stone-500 uppercase tracking-[0.08em] mb-2.5">
            Seu Progresso
        </h3>
        <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 mb-2">
            <div class="h-full rounded-full transition-all duration-500"
                 style="width: {{ $progress }}%; background: linear-gradient(90deg, #2B3A8F, #D95F02)"></div>
        </div>
        <div class="flex justify-between text-[0.78rem] text-gray-500 dark:text-gray-400">
            <span><strong class="text-gray-800 dark:text-gray-200">{{ $readCount }}</strong> de {{ $total }} aulas</span>
            <span>{{ $progress }}%</span>
        </div>
    </div>

    {{-- Lista de módulos/aulas diretamente na sidebar (sem card extra) --}}
    {!! $navHtml !!}
</aside>

{{-- ─── Mobile floating trigger ──────────────────────────────────── --}}
<button
    type="button"
    data-side-panel-open="#{{ $panelId }}" data-side-panel-overlay="#{{ $overlayId }}"
    class="lg:hidden fixed right-4 top-20 z-40 inline-flex items-center gap-2 px-3 py-2 rounded-lg border
           bg-white/90 dark:bg-gray-800/90 shadow-md text-sm font-medium text-brand dark:text-indigo-300
           border-brand-light dark:border-indigo-900 opacity-50 hover:opacity-100"
>
    <x-heroicon-o-list-bullet class="w-4 h-4"/>
    Índice
</button>

{{-- ─── Mobile overlay ────────────────────────────────────────────── --}}
<div id="{{ $overlayId }}" class="lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-200 z-40"></div>

{{-- ─── Mobile drawer panel ───────────────────────────────────────── --}}
<aside id="{{ $panelId }}" class="fixed top-0 left-0 h-full w-4/5 max-w-xs bg-white dark:bg-gray-900 transform -translate-x-full scale-95 transition-transform duration-300 ease-[cubic-bezier(0.22,1,0.36,1)] z-50 p-5 opacity-0 transition-opacity shadow-2xl ring-1 ring-black/5">
    <div class="flex items-start justify-between mb-3">
        <div>
            <h2 class="text-[0.68rem] font-extrabold text-gray-500 uppercase tracking-[0.08em]">Seu Progresso</h2>
            <span class="text-xs text-gray-400">{{ $readCount }}/{{ $total }} · {{ $progress }}%</span>
        </div>
        <button type="button"
                data-side-panel-close data-side-panel-target="#{{ $panelId }}" data-side-panel-overlay="#{{ $overlayId }}"
                class="px-2 py-1 rounded border text-sm text-gray-500">Fechar</button>
    </div>
    <div class="h-1.5 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
        <div class="h-full rounded-full" style="width: {{ $progress }}%; background: linear-gradient(90deg, #2B3A8F, #D95F02)"></div>
    </div>
    {!! $navHtml !!}
</aside>
