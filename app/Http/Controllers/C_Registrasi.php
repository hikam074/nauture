<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class C_Registrasi extends Controller
{

    public function showFormRegistrasiAkun() {
        return view('auth.V_FormRegistrasi');
    }

    public function checkInputDataValid(Request $request) {
        // aturan input
        $rules = [
            'name' => 'required|string|max:128',
            'email' => 'required|string|email|max:128|unique:users',
            'password' => 'required|string|min:8|max:128',
            'no_telp' => 'required|string|max:19|unique:users',
        ];
        // pesan error validasi
        $pesan = [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'no_telp.required' => 'Nomor telepon wajib diisi.',
            'no_telp.max' => 'Nomor telepon tidak boleh lebih dari 19 karakter.',
            'no_telp.unique' => 'Nomor telepon ini sudah digunakan.',
        ];

        // Buat validasi manual
        $validator = Validator::make($request->all(), $rules, $pesan);

        // kalo gagal validasi
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->except('password'));
        }
        else {
            // lanjut periksa akun null tidaknya
            return $this->countDataAkun($request);
        }
    }

    public function countDataAkun(Request $request) {
        // Cek apakah email sudah terdaftar
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', [
                    'title' => 'Gagal',
                    'message'  => 'Email sudah digunakan. Gunakan email lain.'
                ])->withInput($request->except('email'));
        }
        // Cek apakah no_telp sudah terdaftar
        $existingNumber = User::where('no_telp', $request->no_telp)->first();
        if ($existingNumber) {
            return redirect()->back()->with('error', [
                    'title' => 'Gagal',
                    'message'  => 'Nomor telepon sudah digunakan. Gunakan nomor lain.'
                ])->withInput($request->except('no_telp'));
        }
        return $this->register($request);
    }

    public function register(Request $request) {
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
            return redirect()->intended(route('homepage'))->with('success', [
                    'title' => 'Registrasi Berhasil!',
                    'message'  => 'Registrasi Berhasil! Selamat datang!'
                ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', [
                    'title' => 'Kesalahan Sistem',
                    'message'  => 'Tidak dapat memproses registrasi. Silakan coba lagi.'
                ])
            ->withInput($request->except('password'));
        }
    }

}
