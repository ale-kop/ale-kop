{{-- "Continue lendo": últimos posts avulsos em cards no padrão "vidro" do Radiant
     (moldura translúcida + anel interno + card branco), sobre o gradiente da marca,
     no mesmo container do footer. --}}
@props(['posts'])

@if($posts->isNotEmpty())
    <section class="article-next relative" aria-labelledby="article-next-title">
        {{-- Gradiente atrás da metade de baixo dos cards: é o que dá o efeito de vidro na moldura --}}
        <div aria-hidden="true"
             class="absolute inset-x-2 top-36 bottom-0 rounded-4xl bg-linear-115 from-accent-light from-28% via-brand-light via-70% to-brand/60 ring-1 ring-black/5 ring-inset sm:bg-linear-145"></div>

        <div class="relative max-w-7xl mx-auto px-4 xl:px-0">
            <h2 id="article-next-title">Continue lendo</h2>
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                @foreach($posts as $item)
                    <div class="-m-2 grid grid-cols-1 rounded-4xl shadow-[inset_0_0_2px_1px_#ffffff4d] ring-1 ring-black/5">
                        <div class="grid grid-cols-1 rounded-4xl p-2 shadow-md shadow-black/5">
                            <a href="{{ route('posts.show', $item->slug) }}"
                               class="group flex flex-col rounded-3xl bg-white p-8 pb-7 shadow-2xl ring-1 ring-black/5">
                                @if($item->tag)
                                    <span class="font-sans text-xs/5 font-semibold tracking-widest text-gray-500 uppercase">{{ $item->tag->name }}</span>
                                @endif
                                <h3 class="mt-3 font-serif text-xl/[1.35] font-semibold text-gray-950 transition-colors group-hover:text-brand-deep">{{ $item->name }}</h3>
                                <time datetime="{{ $item->created_at->toDateString() }}"
                                      class="mt-auto pt-6 font-sans text-sm/6 text-gray-950/60">{{ $item->created_at->locale('pt_BR')->translatedFormat('j M Y') }}</time>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
