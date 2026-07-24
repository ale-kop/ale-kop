<x-layout title="Minha área">
    <x-container>
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="font-mono text-xs/5 font-semibold tracking-widest text-gray-500 uppercase">Minha área</p>
                <h1 class="mt-2 text-4xl font-serif font-medium tracking-tighter text-gray-950">Olá, {{ $user->name }}</h1>
                <p class="mt-2 text-gray-500">Acompanhe seus cursos e continue de onde parou.</p>
            </div>

            @if($user->isAdmin())
                <a href="{{ route('admin.index') }}" class="inline-flex items-center justify-center rounded-lg bg-gray-950 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                    Painel admin
                </a>
            @endif
        </div>

        @if(session('status'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="mb-8 rounded-xl border border-gray-200 bg-white p-5">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="font-semibold text-gray-950">Acesso full</h2>
                    <p class="mt-1 text-sm text-gray-500">Libera todos os cursos atuais e futuros enquanto o acesso estiver ativo.</p>
                </div>
                @if($hasFullAccess)
                    <span class="inline-flex items-center justify-center rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700">Ativo</span>
                @else
                    <form method="POST" action="{{ route('courses.full-access.purchase') }}">
                        @csrf
                        <x-forms.button type="submit" class="!bg-brand hover:!bg-brand/90">
                            Comprar acesso full
                        </x-forms.button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($courses as $course)
                @php
                    $canAccess = (bool) $course->getAttribute('can_access');
                    $progress = (int) $course->getAttribute('progress_percent');
                    $read = (int) $course->getAttribute('read_count');
                    $total = $course->posts->count();
                @endphp
                <div class="rounded-xl border border-gray-200 bg-white p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="font-semibold text-gray-950">{{ $course->name }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $read }} de {{ $total }} aulas concluídas</p>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $canAccess ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $canAccess ? 'Liberado' : 'Bloqueado' }}
                        </span>
                    </div>

                    <div class="mt-4 h-2 rounded-full bg-gray-100">
                        <div class="h-full rounded-full bg-emerald-500" style="width: {{ $progress }}%"></div>
                    </div>
                    <div class="mt-1 text-right text-xs text-gray-500">{{ $progress }}%</div>

                    <div class="mt-5 flex items-center gap-2">
                        <a href="{{ route('courses.show', $course->slug) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-200 px-3 py-2 text-sm font-semibold text-gray-700 hover:border-brand hover:text-brand">
                            Ver curso
                        </a>
                        @unless($canAccess)
                            <form method="POST" action="{{ route('courses.purchase', $course) }}">
                                @csrf
                                <x-forms.button type="submit" class="!bg-brand hover:!bg-brand/90">
                                    Comprar curso
                                </x-forms.button>
                            </form>
                        @endunless
                    </div>
                </div>
            @endforeach
        </div>
    </x-container>
</x-layout>
