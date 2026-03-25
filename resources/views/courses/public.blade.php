<x-layout title="Cursos">
<div class="-mt-30 bg-cream min-h-screen">

    {{-- ═══ HERO ════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden">
        <div class="w-full absolute top-0 h-[400px] z-1 opacity-60
                    bg-gradient-to-b from-white via-teal-50 to-transparent"></div>
        <div class="hero-grid absolute inset-0 pointer-events-none z-2 animate-ambient-fade-in"></div>

        <section class="relative z-10 max-w-[1100px] mx-auto px-6 pt-[calc(5rem+72px)] pb-12 text-center">
            <div class="inline-flex items-center gap-1.5 text-xs font-bold tracking-widest
                        uppercase text-accent bg-accent/10 border border-accent/20
                        px-3 py-1.5 rounded-full mb-5 animate-fade-up" style="animation-delay:.1s">
                ✦ Aprenda no seu ritmo
            </div>
            <h1 class="font-display text-4xl font-black leading-tight tracking-tight text-gray-900 mb-4
                       animate-fade-up" style="animation-delay:.2s">
                Cursos <em class="not-italic text-brand">práticos</em> para profissionais
            </h1>
            <p class="text-base text-gray-500 leading-relaxed max-w-lg mx-auto
                      animate-fade-up" style="animation-delay:.3s">
                Conteúdo direto ao ponto. Sem enrolação, sem teoria inútil.
                Aprenda o que realmente funciona no dia a dia.
            </p>
        </section>
    </div>

    {{-- ═══ GRID DE CURSOS ════════════════════════════════════════════ --}}
    <div class="border-t border-stone-200">
        <div class="max-w-[1100px] mx-auto px-6 py-14">

            @if($courses->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-10">
                    @foreach($courses as $course)
                        <a href="{{ data_get($course->extra, 'custom_url') ?: route('courses.show', $course->slug) }}"
                           title="{{ $course->name }}"
                           class="group col-span-1 h-64 xl:h-72 bg-no-repeat bg-cover bg-center rounded-xl relative shadow-sm
                                  hover:shadow-[0_8px_24px_rgba(43,58,143,0.1)] hover:-translate-y-0.5
                                  transition-all duration-500 ease-in-out overflow-hidden"
                           style="background-image: url({{ $course->getMedia('course-image')->first() ? $course->image('large') : asset('img/home-office-trabalho-remoto.webp') }})">

                            {{-- Gradient overlay --}}
                            <div class="absolute rounded-b-xl bottom-0 inset-x-0 from-gray-900/50 bg-linear-to-t h-40"></div>

                            {{-- Card bottom --}}
                            <div class="flex flex-col absolute bottom-0 w-full mx-auto inset-x-0 text-left
                                        bg-white/95 group-hover:bg-white rounded-b-xl px-4 h-32 max-h-32
                                        justify-center transition-colors duration-300">
                                <span style="text-wrap: balance"
                                      class="text-gray-700 text-lg font-bold mb-1.5 leading-snug">
                                    {{ $course->name }}
                                </span>
                                @if($desc = data_get($course->meta, 'description'))
                                    <small class="text-gray-500 text-sm line-clamp-2">{{ $desc }}</small>
                                @endif
                            </div>

                            {{-- Destaque badge --}}
                            @if(data_get($course->extra, 'featured'))
                                <div class="absolute top-3 right-3 bg-accent text-white text-[10px] font-bold
                                            px-2 py-1 rounded-full tracking-wide">
                                    Destaque
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <div class="text-5xl mb-4">📚</div>
                    <p class="text-gray-500">Nenhum curso disponível no momento.</p>
                </div>
            @endif

        </div>
    </div>

</div>
</x-layout>
