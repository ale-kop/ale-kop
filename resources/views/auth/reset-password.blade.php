<x-layouts.form title="Reset Password">
    <h1 class="text-xl font-bold">Reset Password</h1>
    @if ($errors->any())
        <div>{{ implode(', ', $errors->all()) }}</div>
    @endif
    <form method="POST" action="{{ url('/reset-password') }}" class="mt-6 space-y-6">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <x-forms.label for="email">Email</x-forms.label>
            <x-forms.input id="email" name="email" type="email" value="{{ old('email', request('email')) }}" class="mt-2" />
        </div>
        <div>
            <x-forms.label for="password">Password</x-forms.label>
            <x-forms.input id="password" name="password" type="password" class="mt-2" />
        </div>
        <div>
            <x-forms.label for="password_confirmation">Confirm Password</x-forms.label>
            <x-forms.input id="password_confirmation" name="password_confirmation" type="password" class="mt-2" />
        </div>
        <x-forms.button type="submit">Reset Password</x-forms.button>
    </form>
</x-layouts.form>
