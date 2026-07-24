<x-layout title="Conta">
    <x-container>
        <div class="mb-8 flex items-start justify-between gap-4">
            <div>
                <a href="{{ route('admin.index') }}" class="text-sm text-gray-500 hover:text-brand transition-colors">← Admin</a>
                <h1 class="mt-3 text-3xl font-bold text-gray-900 dark:text-gray-100">Conta</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">{{ auth()->user()->name }} · {{ auth()->user()->email }}</p>
            </div>
        </div>

        <div class="max-w-xl rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand/10">
                    <x-heroicon-o-lock-closed class="h-5 w-5 text-brand"/>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-900 dark:text-gray-100">Senha</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Atualize a credencial usada para acessar o editor.</p>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.account.password.update') }}" class="space-y-5">
                @csrf
                @method('PATCH')

                <div>
                    <x-forms.label for="current_password">Senha atual</x-forms.label>
                    <x-forms.input id="current_password" name="current_password" type="password" autocomplete="current-password" />
                    @error('current_password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-forms.label for="password">Nova senha</x-forms.label>
                    <x-forms.input id="password" name="password" type="password" autocomplete="new-password" />
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-forms.label for="password_confirmation">Confirmar nova senha</x-forms.label>
                    <x-forms.input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
                </div>

                <div class="flex justify-end">
                    <x-forms.button type="submit">
                        <x-heroicon-o-check class="h-4 w-4"/>
                        Salvar senha
                    </x-forms.button>
                </div>
            </form>
        </div>
    </x-container>
</x-layout>
