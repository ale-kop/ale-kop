<x-layout :title="$course->name">

    {{-- ═══ HERO ════════════════════════════════════════════════════════ --}}
    @php
        $readCount = $course->posts->filter(fn($p) => (bool)($p->is_read ?? false))->count();
        $total = $course->posts->count();
        $progress = $total > 0 ? round($readCount / $total * 100) : 0;
        $desc = data_get($course->meta, 'description');
    @endphp

    <x-container class="text-center">
        <p class="font-mono text-xs/5 font-semibold tracking-widest text-gray-500 uppercase">
            <a href="{{ route('courses.index') }}" class="hover:text-gray-950 transition-colors">Cursos</a>
            <span class="mx-1 text-gray-300">/</span>
            <span class="text-gray-700">{{ $course->name }}</span>
        </p>
        <h1 class="mt-2 font-serif text-4xl font-medium tracking-tighter text-pretty text-gray-900 sm:text-6xl">
            {{ $course->name }}
        </h1>
        @if($desc)
            <p class="mt-6 text-center mx-auto max-w-2xl text-xl font-medium text-gray-500">{{ $desc }}</p>
        @endif

        @if($total > 0)
            <div class="mt-6 flex flex-wrap justify-center items-center gap-x-6 gap-y-3 text-sm/6 text-gray-500">
                <span class="flex items-center gap-1.5">
                    <x-heroicon-o-document-text class="w-4 h-4"/>
                    {{ $total }} {{ Str::plural('aula', $total) }}
                </span>
                <span class="flex items-center gap-1.5">
                    <x-heroicon-o-check-circle class="w-4 h-4"/>
                    {{ $readCount }}/{{ $total }} lidas
                </span>
                <div class="flex items-center gap-2">
                    <div class="w-32 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full transition-all"
                             style="width: {{ $progress }}%"></div>
                    </div>
                    <span class="text-xs font-medium text-gray-700">{{ $progress }}%</span>
                </div>
            </div>
        @endif
    </x-container>

    {{-- ═══ CONTEÚDO ══════════════════════════════════════════════════ --}}
    <x-container class="mt-16 pb-24">

        @if($course->posts && $course->posts->count())

            <div class="max-w-2xl mx-auto">
                <p class="font-mono text-xs/5 font-semibold tracking-widest text-gray-500 uppercase mb-5">
                    Conteúdo do curso
                </p>

                <div class="rounded-2xl border border-gray-200 overflow-hidden bg-white">
                    @foreach($course->posts as $i => $post)
                        @php
                            $isRead = (bool) ($post->is_read ?? false);
                        @endphp
                        <a href="{{ route('posts.show', $post->slug) }}"
                           class="flex items-center gap-5 px-6 py-5 border-b border-gray-100 last:border-0 hover:bg-brand/5 transition-colors group">

                            {{-- Número / check --}}
                            <div class="w-10 h-10 rounded-full shrink-0 flex items-center justify-center text-base font-semibold
                                        {{ $isRead ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-700' }}">
                                @if($isRead)
                                    <x-heroicon-s-check class="w-5 h-5"/>
                                @else
                                    {{ $i + 1 }}
                                @endif
                            </div>

                            {{-- Thumb --}}
                            <div class="w-14 h-14 rounded-lg overflow-hidden shrink-0">
                                @if($url = $post->image('thumb'))
                                    <img src="{{ $url }}" alt="{{ $post->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                                        <x-heroicon-o-document-text class="w-6 h-6"/>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="text-lg font-serif font-semibold text-gray-950 leading-snug">
                                    {{ $post->name }}
                                </div>
                                @if($d = data_get($post->meta, 'description'))
                                    <div class="text-sm text-gray-500 mt-1 line-clamp-1">{{ $d }}</div>
                                @endif
                            </div>

                            {{-- Badge lido/não lido --}}
                            <div class="shrink-0">
                                <span class="text-xs font-medium px-2 py-0.5 rounded {{ $isRead ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $isRead ? 'Lido' : 'Não lido' }}
                                </span>
                            </div>

                            <x-heroicon-s-chevron-right class="w-5 h-5 text-gray-300 group-hover:text-gray-950 transition-colors shrink-0"/>
                        </a>
                    @endforeach
                </div>
            </div>

        @else
            <div class="text-center py-20">
                <p class="text-gray-500">Este curso ainda não possui aulas publicadas.</p>
                <a href="{{ route('courses.index') }}"
                   class="inline-flex items-center gap-1 mt-4 text-sm font-medium text-gray-950 hover:underline">
                    Ver outros cursos
                    <x-heroicon-s-chevron-right class="size-4"/>
                </a>
            </div>
        @endif

    </x-container>

</x-layout>
