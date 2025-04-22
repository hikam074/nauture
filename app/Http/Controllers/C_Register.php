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
        $validated = $request->validate([
            'name' => 'required|string|max:128',
            'email' => 'required|string|email|max:128|unique:users',
            'password' => 'required|string|min:8|max:128',
            'no_telp' => 'required|string|max:19|unique:users',
        ],
        // pesan error validasi
        [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'no_telp.required' => 'Nomor telepon wajib diisi.',
            'no_telp.max' => 'Nomor telepon tidak boleh lebih dari 19 karakter.',
            'no_telp.unique' => 'Nomor telepon ini sudah digunakan.',
        ]);

        // Cek apakah email sudah terdaftar
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            $request->session()->flash('error', 'Email sudah digunakan. Gunakan email lain.');
            return redirect()->back()->withInput($request->only('email'));
        }
        // Cek apakah email sudah terdaftar
        $existingNumber = User::where('no_telp', $request->no_telp)->first();
        if ($existingNumber) {
            $request->session()->flash('error', 'Nomor telepon sudah digunakan. Gunakan nomor lain.');
            return redirect()->back()->withInput($request->only('no_telp'));
        }

        try {
            // Simpan pengguna baru ke database
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 3,
                'isSuspeded' => false,
                'no_telp' => $request->no_telp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // Login pengguna setelah registrasi
            Auth::login($user);
            $request->session()->regenerate();
            // Redirect ke homepage
            $request->session()->flash('success', 'Registrasi Berhasil!');
            return redirect()->intended(route('homepage'));
        } catch (\Exception $e) {
            $request->session()->flash('error', 'Terjadi kesalahan sistem : Tidak dapat memproses registrasi. Silakan coba lagi.');
            return redirect()->back()->withInput($request->except('password'));
        }
    }
}
