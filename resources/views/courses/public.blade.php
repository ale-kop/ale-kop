<x-layout title="Cursos">

    {{-- ═══ HERO ════════════════════════════════════════════════════════ --}}
    <x-container>
        <p class="font-sans text-xs/5 font-semibold tracking-widest text-gray-500 uppercase">
            Cursos
        </p>
        <h1 class="mt-2 font-sans font-bold tracking-[-0.035em] text-4xl text-pretty text-gray-900 sm:text-6xl">
            Aprenda no seu ritmo
        </h1>
        <p class="mt-6 max-w-5xl text-2xl font-medium text-gray-500">
            Conteúdo direto ao ponto, sem enrolação. Aprenda o que realmente
            funciona no dia a dia.
        </p>
    </x-container>

    {{-- ═══ GRID DE CURSOS ════════════════════════════════════════════ --}}
    <x-container class="mt-10 pb-24">

        @if($courses->isNotEmpty())

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($courses as $course)
                    @php
                        $hasImage = $course->getMedia('course-image')->first();
                        $bg = $hasImage ? $course->image('large') : asset('img/home-office-trabalho-remoto.webp');
                        $desc = data_get($course->meta, 'description');
                    @endphp
                    <a href="{{ data_get($course->extra, 'custom_url') ?: route('courses.show', $course->slug) }}"
                       title="{{ $course->name }}"
                       class="group relative flex flex-col rounded-3xl bg-white p-2 ring-1 ring-black/5 shadow-md shadow-black/5 transition-all hover:shadow-lg hover:-translate-y-0.5">

                        <div class="aspect-3/2 w-full rounded-2xl bg-cover bg-center"
                             style="background-image: url('{{ $bg }}')"></div>

                        <div class="flex flex-1 flex-col p-6">
                            <div class="text-2xl/7 font-bold font-sans tracking-[-0.025em] text-gray-800 group-hover:text-gray-700 transition-colors">
                                {{ $course->name }}
                            </div>
                            @if($desc)
                                <p class="mt-2 flex-1 font-serif text-base/relaxed text-gray-600 line-clamp-3">{{ $desc }}</p>
                            @endif
                            <div class="mt-6 flex font-sans items-center gap-2 text-base/5 font-semibold text-gray-950">
                                Ver curso →
                            </div>
                        </div>

                        @if(data_get($course->extra, 'featured'))
                            <span class="absolute top-4 right-4 rounded-full bg-gray-950 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wider text-white">
                                Destaque
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>

        @else

            <p class="mt-10 text-gray-500">Nenhum curso disponível no momento.</p>

        @endif

    </x-container>

</x-layout>