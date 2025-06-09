<?php

namespace App\Http\Controllers;

use App\Models\M_Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class C_Pegawai extends Controller
{
    public function getDataProfilPegawai()
    {
        $roleIdPegawai = M_Role::where('nama_role', 'pegawai')->first()->id;
        $pegawais = User::with('alamat.city.provinsi')->where('role_id', $roleIdPegawai)->paginate(10);
        return $this->showDataProfilPegawai($pegawais);
    }

    public function showDataProfilPegawai($pegawais)
    {
        return view('dashboard.d-pegawai.V_HalamanPegawai', compact('pegawais'));
    }




    public function showFormAddPegawai()
    {
        return view('dashboard.d-pegawai.V_FormTambahPegawai');
    }

    public function tambahkanPegawai(Request $request)
    {
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

        try {
            // Simpan pengguna baru ke database
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 2,
                'suspend_point' => 0,
                'no_telp' => $request->no_telp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Redirect ke homepage
            return redirect()->route('pegawai.index')->with('success', [
                    'title' => $request->name.' berhasil ditambahkan!',
                    'message'  => 'Silahkan minta pegawai untuk melengkapi data lainnya secara mandiri'
                ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', [
                    'title' => 'Kesalahan Sistem',
                    'message'  => 'Tidak dapat memproses data. Silakan coba lagi.'
                ])
            ->withInput($request->except('password'));
        }
    }
}
