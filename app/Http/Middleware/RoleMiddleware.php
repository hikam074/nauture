<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // cek apakah user punya role
        if (!isset(Auth::user()->role)) {
            return abort(403, 'User does not have a role assigned.');
        }

        // cek apakah role user sesuai
        if (Auth::user()->role->nama_role !== $role) {
            return abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
