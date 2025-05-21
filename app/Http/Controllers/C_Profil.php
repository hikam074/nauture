<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class C_Profil extends Controller
{
    public function showDataProfil()
    {
        return view('dashboard.V_HalamanProfil');
    }

    public function updateDataProfil(Request $request)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        if ($field === 'password') {
            // Validasi khusus untuk password
            $request->validate([
                'new_password' => 'required|string|min:8|confirmed',
            ], [
                'new_password.required' => 'Password baru wajib diisi.',
                'new_password.min' => 'Password baru harus minimal 8 karakter.',
                'new_password.confirmed' => 'Konfirmasi password tidak sesuai.',
            ]);

            // Simpan password baru
            $user = User::find(Auth::id());
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->back()->with('success', [
                'title' => 'Berhasil',
                'message' => 'Password berhasil diperbarui.',
            ]);
        }

        // Aturan validasi per field
            $rules = [
                'name' => 'required|string|max:128',
                'email' => 'required|string|email|max:128|unique:users,email,' . Auth::id(),
                'no_telp' => 'required|string|max:19|unique:users,no_telp,' . Auth::id(),
                'alamat' => 'nullable|string|max:255',
            ];

            // Pesan error
            $pesan = [
                'name.required' => 'Nama lengkap wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.unique' => 'Email sudah digunakan.',
                'no_telp.required' => 'Nomor telepon wajib diisi.',
                'no_telp.unique' => 'Nomor telepon sudah digunakan.',
                'alamat.max' => 'Alamat terlalu panjang.',
            ];

            // Validasi input
            $validator = Validator::make($request->all(), [
                'value' => $rules[$field] ?? 'nullable|string',
            ], $pesan);

            if ($validator->fails()) {
                return redirect()->back()->withErrors([$field => $validator->errors()->first('value')])->withInput();
            }

            // Simpan data
            $user = User::find(Auth::id());
            $user->$field = $request->value;
            $user->save();

            return redirect()->back()->with('success', [
                'title' => 'Berhasil',
                'message' => ucfirst($field) . ' berhasil diperbarui!',
            ]);
    }





    public function klikLogout(Request $request) {
        $request->session()->flash('alert', [
            'title' => 'Logout?',
            'text' => 'Apakah anda yakin ingin keluar?',
            'icon' => 'warning',
            'confirmButtonText' => 'Ya, Logout!',
            'cancelButtonText' => 'Batal',
            'confirmUrl' => route('logout.process'),
        ]);

        return redirect()->back();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('homepage'))->with('success', [
                    'title' => 'Berhasil Logout!',
                    'message'  => 'Silahkan login untuk menggunakan lebih banyak fitur'
            ]);
    }
}
