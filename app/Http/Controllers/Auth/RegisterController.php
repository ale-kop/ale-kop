<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth\RegistrationException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function show(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if (User::where('email', $validated['email'])->exists()) {
            throw new RegistrationException('User already exists.');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'api_token' => Str::random(60),
        ]);

        Auth::login($user);

        if ($to = $this->safeRedirect($request->input('redirect'))) {
            return redirect()->to($to);
        }

        return redirect()->intended('/dashboard');
    }

    /**
     * Only allow relative, same-site paths as a post-auth redirect target.
     */
    private function safeRedirect(mixed $target): ?string
    {
        if (! is_string($target) || $target === '') {
            return null;
        }

        // Relative same-site path (reject protocol-relative "//host").
        if (str_starts_with($target, '/') && ! str_starts_with($target, '//')) {
            return $target;
        }

        // Absolute URL, but only on this application's host.
        if (parse_url($target, PHP_URL_HOST) === parse_url(config('app.url'), PHP_URL_HOST)) {
            return $target;
        }

        return null;
    }
}
