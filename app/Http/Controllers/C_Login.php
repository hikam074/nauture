<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class C_Login extends Controller
{
    public function show(Request $request) {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // ambil var
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $remember = $request->has('remember');

        // Periksa apakah email ada di database
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user) {
            $request->session()->flash('error', 'Email tidak ditemukan, silahkan periksa lagi');
            return redirect()->back()->withInput($request->except('password'));
        }

        // Periksa password
        if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            $request->session()->flash('error', 'Password tidak cocok. Silahkan coba lagi.');
            return redirect()->back()->withInput($request->except('password'));
        }

        // Login berhasil
        $request->session()->regenerate();
        $request->session()->flash('success', 'Login berhasil!');
        return redirect()->route('homepage');

    }

    public function homepage()
    {
        return view('homepage');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
