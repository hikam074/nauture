<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        Log::info('Middleware Role dijalankan.');

        if (!Auth::check()) {
            dd("User belum login");
            return redirect()->route('login'); // Arahkan ke login jika belum login
        }

        $user = Auth::user();

        // Pastikan user memiliki role
        if (!$user->role) {
            dd("User tidak memiliki role");
            abort(403, 'Role tidak ditemukan.');
        }

        // Periksa apakah nama role ada di daftar yang diperbolehkan
        if (!in_array($user->role->nama, $roles)) {
            dd("Role user tidak sesuai. Role saat ini: " . $user->role->nama);
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
