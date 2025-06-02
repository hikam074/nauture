<?php

namespace App\Http\Controllers;

use App\Models\M_Alamat;
use App\Models\M_Provinsi;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class C_Profil extends Controller
{
    public function showHalamanDashboard()
    {
        return view('dashboard.dashboard');
    }




    public function getDataProfil()
    {
        $profil = User::with(['role', 'alamat.city.provinsi'])->find(Auth::user()->id);
        $profil->role->nama_role = ucwords($profil->role->nama_role);

        return $this->showHalamanProfil($profil);
    }

    public function showHalamanProfil($profil)
    {
        return view('dashboard.V_HalamanProfil', compact('profil'));
    }




    public function editDataProfil(Request $request) {
        $profil = User::with(['role', 'alamat.city.provinsi'])->find(Auth::user()->id);
        $provinsis = M_Provinsi::all();
        return view('dashboard.V_FormUbahProfil', compact('profil', 'provinsis'));
    }

    public function checkInputDataTerbaru(Request $request)
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
        }

        // Aturan validasi per field
        $rules = [
            'name' => 'required|string|max:128',
            'email' => 'required|string|email|max:128|unique:users,email,' . Auth::id(),
            'no_telp' => 'required|string|max:19|unique:users,no_telp,' . Auth::id(),
            'foto_profil' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'provinsi_id' => 'required|exists:provinsis,id',
            'city_id' => 'required|exists:cities,id',
            'detail_alamat' => 'required|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
        ];
        // Pesan error
        $pesan = [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'no_telp.required' => 'Nomor telepon wajib diisi.',
            'no_telp.unique' => 'Nomor telepon sudah digunakan.',
            'foto_profil.required' => 'Foto profil wajib diisi.',
            'provinsi_id.required' => 'Provinsi wajib dipilih.',
            'provinsi_id.exists' => 'Provinsi yang dipilih tidak valid.',
            'city_id.required' => 'Kota wajib dipilih.',
            'city_id.exists' => 'Kota yang dipilih tidak valid.',
            'detail_alamat.required' => 'Detail alamat wajib diisi.',
            'detail_alamat.max' => 'Detail alamat terlalu panjang.',
            'kode_pos.max' => 'Kode pos terlalu panjang.',

        ];

        // Validasi input
        $validator = Validator::make($request->all(), [
            'value' => $rules[$field] ?? 'nullable|string',
        ], $pesan);

        if ($validator->fails()) {
            return redirect()->back()->withErrors([$field => $validator->errors()->first('value')])->withInput();
        }

        return $this->updateDataProfil($request);
    }

    public function updateDataProfil(Request $request)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        // Simpan data
        $user = User::find(Auth::id());

        if ($field === 'password') {
            // Simpan password baru
            $user->password = Hash::make($request->new_password);
        }
        else if ($field === 'foto_profil') {
            if ($request->hasFile('foto_profil')) {
                // Hapus foto lama jika ada
                if ($user->foto_profil) {
                    Storage::delete($user->foto_profil);
                }
                // Upload foto baru
                $path = $request->file('foto_profil')->store('users', 'public');
                $user->foto_profil = $path;

            } elseif ($request->input('reset') === 'true') {
                // Reset foto menjadi null
                if ($user->foto_profil) {
                    Storage::delete($user->foto_profil);
                }
                $user->foto_profil = null;
            }
        }
        else if ($field === 'alamat') {
            if (!$user->alamat_id) {
                $alamat = M_Alamat::create([
                    'city_id' => $request->city_id,
                    'detail_alamat' => $request->detail_alamat,
                    'kode_pos' => $request->kode_pos
                ]);
                // Perbarui alamat_id di tabel users
                $user->update(['alamat_id' => $alamat->id]);
            }
            else {
                // Jika sudah ada alamat_id, update alamat yang ada
                $alamat = M_Alamat::findOrFail($user->alamat_id);
                $alamat->update([
                    'city_id' => $request->city_id,
                    'detail_alamat' => $request->detail_alamat,
                    'kode_pos' => $request->kode_pos
                ]);
            }
        }
        else {
            $user->$field = $request->value;
        }
        $user->save();

        return redirect(route('profil.index'))->with('success', [
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
