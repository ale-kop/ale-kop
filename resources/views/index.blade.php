<x-layout>

    <div class="w-full z-10 pt-28 pb-32 bg-sky-800 flex flex-col space-y-3 px-4 items-center">
        <h1 class="text-gray-50 text-4xl font-medium tracking-wide text-center">Programe para a Web</h1>
        <p class="text-gray-50 max-w-3xl text-center pt-1">
            Conteúdo educativo sobre desenvolvimento web, carreira, empreendedorismo, freelancing, e mais!
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 px-6 lg:px-0 max-w-6xl place-content-around mx-auto gap-6 lg:gap-10 -mt-20">
        @forelse($coursesWithContent as $course)
            @if (data_get($course->extra, 'featured'))
                <a href="{{ data_get($course->extra, 'custom_url') ?: route('courses.show', $course->slug) }}"
                   title="Conteúdo sobre {{ $course->name }}"
                   class="hover:bg-bottom col-span-1 h-64 xl:h-72 bg-no-repeat bg-cover bg-center rounded-xl relative shadow-md hover:ring-2 ring-offset-gray-500/10 ring-offset-1 ring-sky-800/20 transition-all duration-500 ease-in-out"
                   style="background-image: url({{ $course->getMedia('course-image')->first() ? $course->image('large') : asset('img/home-office-trabalho-remoto.webp') }})">
                    <div
                         class="absolute rounded-b-xl bottom-0 inset-x-0 from-gray-900/50 bg-gradient-to-t text-center text-white text-xl font-semibold flex h-[10rem] shadow-lg items-center justify-center pt-4 pb-2 px-6">
                    </div>
                    <span style="text-wrap: balance"
                          class="flex items-center absolute bottom-0 w-full mx-auto inset-x-0 text-center bg-gray-800/50 rounded-b-xl px-6 h-32 max-h-32 text-white font-medium tracking-tight justify-center text-xl lg:text-2xl">{{ $course->name }} com mais algo</span>
                </a>
            @endif
        @empty
            Nenhum assunto em destaque encontrado
        @endforelse
    </div>

    <div class="my-6">&nbsp;</div>

    <div class="mx-auto grid max-w-7xl grid-cols-1 gap-x-8 gap-y-12 px-6 sm:gap-y-16 lg:grid-cols-2 lg:px-8">

        @if ($featuredPost)
            <article class="mx-auto w-full max-w-2xl lg:mx-0 lg:max-w-lg">
                <span class="inline-block rounded-2xl bg-gradient-to-r from-amber-100 via-orange-200/75 to-orange-200 px-4 text-sm font-medium leading-6 text-gray-700">{{$featuredPost->tag->name}}</span>
                <h2 id="featured-post"
                    class="mt-2 text-3xl font-bold tracking-tight text-gray-800 sm:text-4xl">{{$featuredPost->name}}</h2>
                <p class="mt-4 text-lg leading-8 text-gray-600">{{$featuredPost->meta['description']}}</p>
                <div
                    class="mt-4 flex flex-col justify-between gap-6 sm:mt-8 sm:flex-row-reverse sm:gap-8 lg:mt-4 lg:flex-col">
                    <div class="flex">
                        <a href="{{route('posts.show',$featuredPost->slug)}}"
                           class="text-sm font-semibold leading-6 text-indigo-600 group"
                           aria-describedby="featured-post">Ler post <div class="inline-block group-hover:translate-x-0.5 transform transition-all duration-300 ease-in-out" aria-hidden="true">&rarr;</div></a>
                    </div>
                </div>
            </article>
        @endif

        <div
            class="mx-auto w-full max-w-2xl border-t border-gray-900/10 pt-12 sm:pt-16 lg:mx-0 lg:max-w-none lg:border-t-0 lg:pt-0">
            <div class="-my-12 divide-y divide-gray-900/10">
                @forelse($latestTwoPosts as $post)
                    <article class="py-12">
                        <div class="group relative max-w-xl">
                            <span class="inline-block rounded-2xl bg-gradient-to-r from-amber-100 via-orange-200/75 to-orange-200 px-4 text-sm font-medium leading-6 text-gray-700">{{$post->tag->name}}</span>
                            <h2 class="mt-2 text-lg lg:text-xl font-semibold text-gray-900 group-hover:text-gray-600">
                                <a href="{{route('posts.show',$post->slug)}}" title="Ir para o post {{$post->name}}">
                                    <span class="absolute inset-0"></span>
                                    {{$post->name}}
                                </a>
                            </h2>
                            <p class="mt-4 text-sm lg:text-base leading-6 text-gray-600">{{$post->meta['description'] ?? ''}}</p>
                        </div>
                    </article>
                @empty
                    Nenhum post
                @endforelse
            </div>
        </div>
    </div>


    <div class="my-6">&nbsp;</div>

    <div class="relative">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="absolute -top-10">
            <path fill="#fff" fill-opacity="1"
                  d="M0,256L80,234.7C160,213,320,171,480,165.3C640,160,800,192,960,208C1120,224,1280,224,1360,224L1440,224L1440,0L1360,0C1280,0,1120,0,960,0C800,0,640,0,480,0C320,0,160,0,80,0L0,0Z">
            </path>
        </svg>
        <div class="from-purple-300/30 to-white bg-gradient-to-b py-10 mt-10 z-0">

            <div class="relative">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div
                        class="mx-auto flex max-w-2xl flex-col gap-16 bg-black/60 px-6 py-16 ring-1 ring-white/10 sm:rounded-3xl sm:p-8 lg:mx-0 lg:max-w-none lg:flex-row lg:items-start lg:py-20 xl:gap-x-20 xl:px-20">
                        <img alt="Mesa com copo de café a imagens com stickers de PHP, JS e fotos pessoais"
                             class="h-96 w-full flex-none rounded-2xl object-cover shadow-xl lg:aspect-square lg:h-auto lg:max-w-sm"
                             src="{{ asset('img/avatar-akop-4.webp') }}">
                        <div class="w-full flex-auto">
                            <h2 class="text-3xl font-medium tracking-tight text-white sm:text-4xl">Conheça o proComercial</h2>
                            <p class="mt-6 text-base leading-7 text-white">
                                👋🏻 Procurando um modelo de proposta comercial, uma planilha de fluxo de caixa ou um modelo de apresentação profissional?
                                O proComercial tem a missão de oferecer muita variedade e qualidade para tarefas profissionais do dia-a-dia: aqui você encontra desde um simples exemplo de recibo,
                                até uma apresentação em PowerPoint de cair o queixo.
                            </p>
                            <ul role="list"
                                class="mt-10 grid grid-cols-1 gap-x-8 gap-y-3 leading-7 text-gray-100 sm:grid-cols-2">
                                <li class="flex gap-x-3">
                                    <svg class="h-7 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    Modelo de Proposta Comercial
                                </li>
                                <li class="flex gap-x-3">
                                    <svg class="h-7 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    Modelos de Planilhas Excel
                                </li>
                                <li class="flex gap-x-3">
                                    <svg class="h-7 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    Modelos de Cartas de Apresentação
                                </li>
                                <li class="flex gap-x-3">
                                    <svg class="h-7 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    Modelos de apresentação PowerPoint
                                </li>
                                <li class="flex gap-x-3">
                                    <svg class="h-7 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    Funções e Fórmulas Excel
                                </li>
                                <li class="flex gap-x-3">
                                    <svg class="h-7 w-5 flex-none" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    Modelos de Currículos
                                </li>
                            </ul>
                            <div class="mt-10 flex group">
                                <a href="#clients"
                                   class="scroll-smooth text-sm font-semibold leading-6 text-indigo-100 hover:text-white">Veja
                                    alguns clientes
                                    <div aria-hidden="true"
                                         class="ml-2 group-hover:translate-y-1 rotate-90 duration-300 ease-in-out transition-all">
                                        &rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-x-0 -top-16 -z-10 flex transform-gpu justify-center overflow-hidden blur-3xl"
                     aria-hidden="true">
                    <div
                        class="aspect-[1318/752] w-[82.375rem] flex-none bg-gradient-to-r from-[#80caff] to-[#4f46e5] opacity-25"
                        style="clip-path: polygon(73.6% 51.7%, 91.7% 11.8%, 100% 46.4%, 97.4% 82.2%, 92.5% 84.9%, 75.7% 64%, 55.3% 47.5%, 46.5% 49.4%, 45% 62.9%, 50.3% 87.2%, 21.3% 64.1%, 0.1% 100%, 5.4% 51.1%, 21.4% 63.9%, 58.9% 0.2%, 73.6% 51.7%)">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-1 items-start gap-x-8 gap-y-16 lg:grid-cols-2 py-10">
                <div class="mx-auto w-full max-w-xl lg:mx-0">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900" id="clients">Clientes atendidos</h2>
                    <p class="mt-6 text-lg leading-8 text-gray-600">Ao longo dos mais de 20 anos de jornada, atendi
                        inúmeras empresas criando sites, softwares de gestão, campanhas de SEO e prestando consultoria
                        para os mais variados fins dentro do espectro de produtos Web.</p>
                    <div class="mt-8 flex group">
                        <a href="#projects"
                           class="active:scale-[0.99] transform transition-all duration-200 ease-in-out rounded-md bg-sky-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Conheça meus principais projetos</a>
                    </div>
                </div>
                <div
                    class="mx-auto grid w-full max-w-xl grid-cols-2 items-center gap-y-12 lg:mx-0 lg:max-w-none lg:pl-8">
                    <img class="max-h-16 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-arysta.webp') }}" title="Arysta" alt="Arysta - Logo">
                    <img class="max-h-16 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-iac.webp') }}" title="Instituto Agronômico de Campinas"
                         alt="Instituo Agronômico de Campinas - Logo">
                    <img class="max-h-20 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-apta.webp') }}" title="APTA SP" alt="APTA Governo SP - Logo">
                    <img class="max-h-16 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-lider-leiloes.webp') }}" title="Líder Leilões"
                         alt="Líder Leilões - Logo">
                    <img class="max-h-16 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-fundepag.webp') }}" title="Fundepag" alt="Fundepag - Logo">
                    <img class="max-h-16 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-capital-consultoria.webp') }}" title="Capital Consultoria"
                         alt="Capital Consultoria - Logo">
                    <img class="max-h-20 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-tdg.webp') }}" title="TDG Informática" alt="TDG - Logo">
                    <img class="max-h-16 w-full object-contain object-left grayscale"
                         src="{{ asset('img/logo-mossebo.webp') }}" title="Mossebo Interior Design"
                         alt="Mossebo Interior Design - Logo">
                </div>
            </div>
        </div>
    </div>


    {{-- App Showcase --}}
    <div class="overflow-hidden bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div
                class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">
                <div class="lg:pr-8 lg:pt-4">
                    <div class="lg:max-w-lg">
                        <h2 class="text-base font-semibold leading-7 text-sky-600" id="projects">Software de Gestão
                        </h2>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">SGP AgroSP</p>
                        <p class="mt-6 text-lg leading-8 text-gray-600">O SGP AgroSP integra as área da pesquisa com
                            outras coordenadorias da Secretaria de Agricultura e Abastecimento, além de promover a
                            interação entre seus institutos na programação de PD&I</p>
                        <dl class="mt-10 max-w-xl space-y-8 text-base leading-7 text-gray-600 lg:max-w-none">
                            <div class="relative pl-9">
                                <dt class="inline font-semibold text-gray-900">
                                    <svg class="absolute left-1 top-1 h-5 w-5 text-sky-600" viewBox="0 0 20 20"
                                         fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M5.5 17a4.5 4.5 0 01-1.44-8.765 4.5 4.5 0 018.302-3.046 3.5 3.5 0 014.504 4.272A4 4 0 0115 17H5.5zm3.75-2.75a.75.75 0 001.5 0V9.66l1.95 2.1a.75.75 0 101.1-1.02l-3.25-3.5a.75.75 0 00-1.1 0l-3.25 3.5a.75.75 0 101.1 1.02l1.95-2.1v4.59z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    Disponível 24/7
                                </dt>
                                <dd class="inline">Software disponível o tempo todo: do computador de mesa, notebook,
                                    tablet ou smartphone. Basta ter uma conexão com a Internet para usá-lo a qualquer
                                    hora e em qualquer lugar.
                                </dd>
                            </div>
                            <div class="relative pl-9">
                                <dt class="inline font-semibold text-gray-900">
                                    <svg class="absolute left-1 top-1 h-5 w-5 text-sky-600" viewBox="0 0 20 20"
                                         fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    Dados em segurança
                                </dt>
                                <dd class="inline">Além de contar com transmissão de dados através do protocolo HTTPS,
                                    está em conformidade com a LGPD, mantendo as informações encriptadas no banco de
                                    dados.
                                </dd>
                            </div>
                            <div class="relative pl-9">
                                <dt class="inline font-semibold text-gray-900">
                                    <svg class="absolute left-1 top-1 h-5 w-5 text-sky-600"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                              d="M10 1a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 1zM5.05 3.05a.75.75 0 011.06 0l1.062 1.06A.75.75 0 116.11 5.173L5.05 4.11a.75.75 0 010-1.06zm9.9 0a.75.75 0 010 1.06l-1.06 1.062a.75.75 0 01-1.062-1.061l1.061-1.06a.75.75 0 011.06 0zM3 8a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5A.75.75 0 013 8zm11 0a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5A.75.75 0 0114 8zm-6.828 2.828a.75.75 0 010 1.061L6.11 12.95a.75.75 0 01-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zm3.594-3.317a.75.75 0 00-1.37.364l-.492 6.861a.75.75 0 001.204.65l1.043-.799.985 3.678a.75.75 0 001.45-.388l-.978-3.646 1.292.204a.75.75 0 00.74-1.16l-3.874-5.764z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    Interface amigável
                                </dt>
                                <dd class="inline">Elaborado com as melhores práticas de User Experience, o SGP AgroSP
                                    possui interface amigável e intuitiva, para todos os usuários.
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
                <img src="{{ asset('img/macbook-sgp-procomercial.webp') }}" alt="Product screenshot"
                     class="w-[57rem] md:w-[40rem] rounded-xl shadow-xl ring-1 ring-gray-400/10 md:-ml-4 lg:-ml-0"
                     width="2432" height="1442">
            </div>
        </div>
    </div>

    <div class="text-center font-5xl text-gray-400 font-medium">Em breve, vou colocar todos os projetos aqui...</div>

    {{-- /App Showcase --}}

</x-layout>
