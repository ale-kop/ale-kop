<x-layouts.form title="Login">
    <h1 class="flex items-center gap-2 text-xl font-bold"><x-heroicon-o-key class="w-6 h-6 text-gray-500"/> Login</h1>
    @if ($errors->any())
        <div>{{ implode(', ', $errors->all()) }}</div>
    @endif

    <form method="POST" action="{{ url('/login') }}" class="mt-6 space-y-6">
        @csrf

        <div>
            <x-forms.label for="email">Email</x-forms.label>
            <x-forms.input icon-left="heroicon-c-envelope" id="email" name="email" type="email" value="{{ old('email') }}" />
        </div>
        <div>
            <x-forms.label for="password">Password</x-forms.label>
            <x-forms.input id="password" name="password" type="password" />
        </div>
        <div class="flex items-center gap-3">
            <x-forms.checkbox id="remember" name="remember" />
            <x-forms.label for="remember">Remember me</x-forms.label>
        </div>
        <div class="flex items-center justify-between">
            <a href="{{ url('/forgot-password') }}" class="text-sm font-semibold text-gray-900">Forgot your password?</a>
            <x-forms.button type="submit">Login</x-forms.button>
        </div>
    </form>
</x-layouts.form>
