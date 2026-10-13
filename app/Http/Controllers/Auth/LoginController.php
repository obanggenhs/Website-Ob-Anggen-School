<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau kata sandi tidak cocok.']);
        }

        $user = $request->user();

        if (! $user->is_active) {
            Auth::logout();

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun ini sedang dinonaktifkan oleh Master Admin.']);
        }

        $request->session()->regenerate();
        $user->forceFill(['last_login_at' => now()])->save();
        activity_log('login', 'Masuk ke dashboard');

        return redirect()->intended(route('dashboard.home'));
    }

    public function logout(Request $request)
    {
        activity_log('logout', 'Keluar dari dashboard');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda sudah keluar.');
    }
}
