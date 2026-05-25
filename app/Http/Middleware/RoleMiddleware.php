<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            if ($request->user()) {
                $role = $request->user()->role;
                return redirect()->route("{$role}.dashboard");
            }
            return redirect()->route('login');
        }

        if ($request->user()->role === 'mahasiswa' && $request->user()->status !== 'approved') {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda belum disetujui oleh Admin.',
            ]);
        }

        return $next($request);
    }
}
