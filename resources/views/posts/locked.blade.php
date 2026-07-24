<x-layout :title="'🔒 ' . $post->name">
    @php
        $returnTo = route('posts.show', $post->slug, false);
    @endphp

    <x-container class="pb-24">
        {{-- Breadcrumb --}}
        <p class="font-mono text-xs/5 font-semibold tracking-widest text-gray-500 uppercase text-center">
            <a href="{{ route('courses.index') }}" class="hover:text-gray-950 transition-colors">Cursos</a>
            <span class="mx-1 text-gray-300">/</span>
            <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-gray-950 transition-colors">{{ $course->name }}</a>
        </p>
        <h1 class="mt-2 text-center font-serif text-3xl font-medium tracking-tighter text-gray-900 sm:text-4xl">
            {{ $post->name }}
        </h1>

        <div class="mx-auto mt-10 max-w-xl rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex justify-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand/10">
                    <x-heroicon-o-lock-closed class="h-7 w-7 text-brand"/>
                </div>
            </div>

            @if($reason === 'auth')
                {{-- ─── Curso grátis: exige cadastro ───────────────────── --}}
                <h2 class="mt-5 text-center text-xl font-semibold text-gray-950">Conteúdo exclusivo</h2>
                <p class="mt-2 text-center text-sm text-gray-500">
                    Cadastre-se grátis para assistir a esta e às demais aulas do curso.
                </p>

                <form method="POST" action="{{ url('/register') }}" class="mt-6 space-y-4">
                    @csrf
                    <input type="hidden" name="redirect" value="{{ $returnTo }}">

                    <div>
                        <x-forms.label for="name">Nome</x-forms.label>
                        <x-forms.input id="name" name="name" type="text" :value="old('name')" autocomplete="name" required />
                        @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-forms.label for="email">Email</x-forms.label>
                        <x-forms.input id="email" name="email" type="email" :value="old('email')" autocomplete="email" required />
                        @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-forms.label for="password">Senha</x-forms.label>
                        <x-forms.input id="password" name="password" type="password" autocomplete="new-password" required />
                        @error('password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-forms.label for="password_confirmation">Confirmar senha</x-forms.label>
                        <x-forms.input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required />
                    </div>

                    <x-forms.button type="submit" class="w-full justify-center !bg-brand hover:!bg-brand/90">
                        Criar conta e assistir
                    </x-forms.button>
                </form>

                <p class="mt-5 text-center text-sm text-gray-500">
                    Já tem conta?
                    <a href="{{ route('login', ['redirect' => $returnTo]) }}" class="font-semibold text-brand hover:underline">Entrar</a>
                </p>
            @else
                {{-- ─── Curso pago: exige compra / assinatura ──────────── --}}
                <h2 class="mt-5 text-center text-xl font-semibold text-gray-950">Curso com acesso pago</h2>
                <p class="mt-2 text-center text-sm text-gray-500">
                    Compre o acesso anual a este curso ou libere todos os cursos por um valor único.
                </p>

                {{-- Compra avulsa do curso (por ano) --}}
                <div class="mt-6 rounded-xl border border-gray-200 p-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="font-semibold text-gray-950">{{ $course->name }}</p>
                            <p class="text-sm text-gray-500">Acesso a este curso por 1 ano</p>
                        </div>
                        @if($course->formattedPrice())
                            <span class="shrink-0 text-lg font-bold text-gray-950">
                                {{ $course->formattedPrice() }}<span class="text-sm font-medium text-gray-500">/ano</span>
                            </span>
                        @endif
                    </div>
                    @auth
                        <form method="POST" action="{{ route('courses.purchase', $course) }}" class="mt-4">
                            @csrf
                            <x-forms.button type="submit" class="w-full justify-center !bg-brand hover:!bg-brand/90">
                                Comprar este curso
                            </x-forms.button>
                        </form>
                    @endauth
                </div>

                {{-- Planos de acesso full --}}
                @if($plans->isNotEmpty())
                    <p class="mt-6 font-mono text-xs/5 font-semibold tracking-widest text-gray-500 uppercase">Todos os cursos</p>
                    <div class="mt-3 space-y-3">
                        @foreach($plans as $plan)
                            <div class="rounded-xl border border-gray-200 p-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-gray-950">{{ $plan->name }}</p>
                                        @if($plan->description)
                                            <p class="text-sm text-gray-500">{{ $plan->description }}</p>
                                        @endif
                                    </div>
                                    <span class="shrink-0 text-lg font-bold text-gray-950">
                                        {{ $plan->formattedPrice() }}<span class="text-sm font-medium text-gray-500">{{ $plan->intervalLabel() }}</span>
                                    </span>
                                </div>
                                @auth
                                    <form method="POST" action="{{ route('plans.subscribe', $plan) }}" class="mt-4">
                                        @csrf
                                        <x-forms.button type="submit" class="w-full justify-center !border !border-gray-200 !bg-white !text-gray-700 hover:!border-brand hover:!text-brand">
                                            Assinar {{ $plan->name }}
                                        </x-forms.button>
                                    </form>
                                @endauth
                            </div>
                        @endforeach
                    </div>
                @endif

                @guest
                    <p class="mt-6 text-center text-sm text-gray-500">
                        <a href="{{ route('login', ['redirect' => $returnTo]) }}" class="font-semibold text-brand hover:underline">Entre</a>
                        ou
                        <a href="{{ url('/register') }}" class="font-semibold text-brand hover:underline">cadastre-se</a>
                        para comprar.
                    </p>
                @endguest
            @endif
        </div>
    </x-container>
</x-layout>
