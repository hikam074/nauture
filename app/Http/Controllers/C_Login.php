<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\M_Role;

class C_Login extends Controller
{
    public function showFormLogin(Request $request) {
        return view('auth.V_FormLogin');
    }

    public function checkInputDataValid(Request $request) {
        // validasi email password
        $validasi = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if ($validasi->fails()) {
            return redirect()->back()->with('error', [
                'title' => 'Gagal',
                'message'  => 'Username atau password anda tidak valid, silahkan coba kembali!'
            ]);
        }
        else {
            // lanjut periksa user
            return $this->countDataEmail($request);
        }
    }

    public function countDataEmail(Request $request) {
        $remember = $request->has('remember');
        // periksa email
        $userAda = User::where('email', $request['email'])->first();
        if ($userAda) {
            $id = User::where('email', $request['email'])->value('id');
            $roleId = User::where('email', $request['email'])->value('role_id');
            $role = M_Role::where('id', $roleId)->value('nama_role');
        // periksa password
            $email = $request->input('email');
            $password = $request->input('password');
            if (!Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
                return redirect()->back()->with('error', [
                    'title' => 'Gagal',
                    'message'  => 'Password tidak cocok, silahkan coba lagi'
                ])->withInput($request->except('password'));
            }
            else
            {
                return $this->login($id, $role, $request);
            }
        }
        else {
            return redirect()->back()->with('error', [
                'title' => 'Gagal',
                'message'  => 'Email tidak ditemukan, silahkan periksa lagi!'
            ])->withInput($request->except('password'));
        }
    }

    public function login(int $id, string $role, Request $request) {
        // login
        $request->session()->regenerate();
        return redirect()->intended(route('homepage'))->with([
            // !!!WARNING
            // 'id' => $id,
            // 'role' => $role,
            // WARNING!!!
            'request' => [$id, $role],
            'success' => [
                    'title' => 'Berhasil Login',
                    'message'  => 'Selamat Datang!'
                ]
        ]);
    }

}
