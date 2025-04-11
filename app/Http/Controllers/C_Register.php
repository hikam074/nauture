<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class C_Register extends Controller
{
    public function show(Request $request) {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'no_telp' => 'required|string|max:13',
        ]);

        // cek apakah email atau nomor telepon sudah terdaftar
        $existingUser = User::where('email', $request->email)
            ->orWhere('no_telp', $request->no_telp)
            ->first();

        if ($existingUser) {
            return redirect()->back()->withErrors([
                'email' => 'Email atau nomor telepon sudah digunakan.',
            ])->withInput($request->except('password'));
        } else {
            // Simpan pengguna baru ke database
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 3,
                'isSuspeded' => false,
                'no_telp' => $request->no_telp,
                // 'alamat' => $request->input('alamat', null),

                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Login pengguna setelah registrasi
            Auth::login($user);
            $request->session()->regenerate();

            // Redirect ke halaman dashboard atau lainnya
            return redirect()->route('homepage')->with('success', 'Berhasil Registrasi!');
        }
    }
}
