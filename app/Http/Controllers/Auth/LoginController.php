<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth\LoginException;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            throw new LoginException('Invalid credentials.');
        }

        $request->session()->regenerate();

        if ($to = $this->safeRedirect($request->input('redirect'))) {
            return redirect()->to($to);
        }

        $target = Auth::user()->isAdmin() ? route('admin.index') : route('dashboard');

        return redirect()->intended($target);
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

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
