<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->to($this->dashboardRouteForUser(Auth::user()));
        }

        return view('core-package::auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->to($this->dashboardRouteForUser(Auth::user()));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    private function dashboardRouteForUser(\App\Models\User $user): string
    {
        if ($user->hasRole('student'))    return route('student.dashboard');
        if ($user->hasRole('teacher'))    return route('teacher.dashboard');
        if ($user->hasRole('librarian'))  return route('librarian.dashboard');
        if ($user->hasRole('accountant')) return route('accountant.dashboard');
        return route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
