@php
    $plusIcon = '<svg viewBox="0 0 15 15" aria-hidden="true" class="absolute size-[15px] fill-black/10"><path d="M8 0H7V7H0V8H7V15H8V8H15V7H8V0Z"/></svg>';
@endphp

<footer>
    <div class="relative bg-linear-115 from-accent-light from-28% via-brand-light via-70% to-brand sm:bg-linear-145">
        <div class="absolute inset-2 rounded-4xl bg-white/80"></div>

        <div class="relative px-4 xl:px-0 max-w-7xl mx-auto">

            {{-- ── Call to action ─────────────────────────────────────── --}}
            <div class="relative pt-20 pb-16 text-center sm:py-24">
                <p class="font-mono text-xs/5 font-semibold tracking-widest text-gray-500 uppercase">
                    Comece agora
                </p>
                <p class="mt-6 font-serif text-3xl font-medium tracking-tight text-gray-900 sm:text-5xl">
                    Pronto para começar?
                    <br/>
                    Explore os cursos hoje.
                </p>
                <p class="font-serif font-medium mx-auto mt-6 max-w-lg text-lg text-gray-500">
                    Conteúdo direto ao ponto, sem enrolação.
                    <br>Aprenda o que realmente funciona.
                </p>
                <div class="mt-6">
                    <a href="{{ route('courses.index') }}"
                       class="inline-flex items-center justify-center rounded-full bg-brand px-4 py-1.5 text-base/6 font-medium text-white hover:bg-brand-deep transition-colors">
                        Ver cursos
                    </a>
                </div>
            </div>

            {{-- ── PlusGrid: logo + sitemap ───────────────────────────── --}}
            <div class="pb-16 relative pt-[calc(--spacing(2)+1px)]">
                {{-- borda dupla superior --}}
                <div aria-hidden="true" class="absolute inset-x-0 top-0 -mx-4 xl:mx-0">
                    <div class="absolute inset-x-0 top-0 border-t border-black/5"></div>
                    <div class="absolute inset-x-0 top-2 border-t border-black/5"></div>
                </div>

                <div class="grid grid-cols-2 gap-y-10 pb-6 lg:grid-cols-6 lg:gap-8">
                    <div class="col-span-2 flex">
                        <div class="relative pt-6 lg:pb-6">
                            <a href="/" class="text-xl font-semibold tracking-tight text-gray-700 font-serif italic dark:text-white">
                                Aleksandr Kopelevitch
                            </a>
                        </div>
                    </div>
                    <div class="col-span-2 grid grid-cols-2 gap-x-8 gap-y-12 lg:col-span-4 lg:grid-cols-subgrid lg:pt-6">

                        {{-- Conteúdo --}}
                        <div>
                            <h3 class="text-sm/6 font-medium text-gray-950/50">Conteúdo</h3>
                            <ul class="mt-6 space-y-4 text-sm/6">
                                <li><a href="{{ route('courses.index') }}" class="font-medium text-gray-950 hover:text-gray-950/75">Cursos</a></li>
                                @php
                                    $featuredTags = ($tagsWithContent ?? collect())
                                        ->filter(fn($t) => data_get($t->extra, 'featured'))
                                        ->take(3);
                                @endphp
                                @foreach($featuredTags as $tag)
                                    <li>
                                        <a href="{{ data_get($tag->extra, 'custom_url') ?: route('posts.index', $tag->slug) }}"
                                           class="font-medium text-gray-950 hover:text-gray-950/75">{{ $tag->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Cursos --}}
                        {{-- <div>
                            <h3 class="text-sm/6 font-medium text-gray-950/50">Cursos</h3>
                            <ul class="mt-6 space-y-4 text-sm/6">
                                @forelse(($coursesWithContent ?? collect())->take(4) as $course)
                                    <li>
                                        <a href="{{ data_get($course->extra, 'custom_url') ?: route('courses.show', $course->slug) }}"
                                           class="font-medium text-gray-950 hover:text-gray-950/75">{{ $course->name }}</a>
                                    </li>
                                @empty
                                    <li class="text-gray-400">—</li>
                                @endforelse
                            </ul>
                        </div> --}}

                        {{-- Suporte --}}
                        <div>
                            <h3 class="text-sm/6 font-medium text-gray-950/50">Suporte</h3>
                            <ul class="mt-6 space-y-4 text-sm/6">
                                <li><a href="mailto:contato@alekop.com" class="font-medium text-gray-950 hover:text-gray-950/75">Contato</a></li>
                                <li><a href="{{ route('login') }}" class="font-medium text-gray-950 hover:text-gray-950/75">Entrar</a></li>
                            </ul>
                        </div>

                        {{-- Empresa --}}
                        <div>
                            <h3 class="text-sm/6 font-medium text-gray-950/50">Empresa</h3>
                            <ul class="mt-6 space-y-4 text-sm/6">
                                <li><a href="#" class="font-medium text-gray-950 hover:text-gray-950/75">Termos de uso</a></li>
                                <li><a href="#" class="font-medium text-gray-950 hover:text-gray-950/75">Privacidade</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── PlusGrid: copyright + sociais ───────────────────────── --}}
            <div class="flex justify-between relative pt-[calc(--spacing(2)+1px)] pb-[calc(--spacing(2)+1px)]">
                <div aria-hidden="true" class="absolute inset-x-0 top-0 -mx-4 xl:mx-0">
                    <div class="absolute inset-x-0 top-0 border-t border-black/5"></div>
                    <div class="absolute inset-x-0 top-2 border-t border-black/5"></div>
                </div>
                <div aria-hidden="true" class="absolute inset-x-0 bottom-0 -mx-4 xl:mx-0">
                    <div class="absolute inset-x-0 bottom-0 border-b border-black/5"></div>
                    <div class="absolute inset-x-0 bottom-2 border-b border-black/5"></div>
                </div>

                <div>
                    <div class="py-3">
                        <div class="text-sm/6 text-gray-950">
                            &copy; {{ date('Y') }} {{ config('app.name', 'App') }}
                        </div>
                    </div>
                </div>

                <div class="flex">
                    <div class="flex items-center gap-8 py-3">
                        <a href="https://x.com" target="_blank" rel="noopener" aria-label="X"
                           class="text-gray-950 hover:text-gray-950/75">
                            <svg viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path d="M12.6 0h2.454l-5.36 6.778L16 16h-4.937l-3.867-5.594L2.771 16H.316l5.733-7.25L0 0h5.063l3.495 5.114L12.6 0zm-.86 14.376h1.36L4.323 1.539H2.865l8.875 12.837z"/>
                            </svg>
                        </a>
                        <a href="https://linkedin.com" target="_blank" rel="noopener" aria-label="LinkedIn"
                           class="text-gray-950 hover:text-gray-950/75">
                            <svg viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path d="M14.82 0H1.18A1.169 1.169 0 000 1.154v13.694A1.168 1.168 0 001.18 16h13.64A1.17 1.17 0 0016 14.845V1.15A1.171 1.171 0 0014.82 0zM4.744 13.64H2.369V5.996h2.375v7.644zm-1.18-8.684a1.377 1.377 0 11.52-.106 1.377 1.377 0 01-.527.103l.007.003zm10.075 8.683h-2.375V9.921c0-.885-.015-2.025-1.234-2.025-1.218 0-1.425.966-1.425 1.968v3.775H6.233V5.997H8.51v1.05h.032c.317-.601 1.09-1.235 2.246-1.235 2.405-.005 2.851 1.578 2.851 3.63v4.197z"/>
                            </svg>
                        </a>
                        <a href="https://github.com" target="_blank" rel="noopener" aria-label="GitHub"
                           class="text-gray-950 hover:text-gray-950/75">
                            <svg viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2 .37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0016 8c0-4.42-3.58-8-8-8z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</footer>
