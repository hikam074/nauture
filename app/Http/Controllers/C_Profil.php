<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class C_Profil extends Controller
{
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
