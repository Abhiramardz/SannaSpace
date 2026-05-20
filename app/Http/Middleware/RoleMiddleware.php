<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if ($role === 'superadmin') {
            if (!Auth::check()) {
                return redirect('/login');
            }
            if (Auth::user()->role !== 'superadmin') {
                return redirect('/');
            }
        }

        if ($role === 'kasir') {
            if (!Auth::check()) {
                return redirect('/login');
            }
            if (Auth::user()->role !== 'kasir') {
                return redirect('/');
            }
        }

        if ($role === 'customer') {
            if (Auth::check() && Auth::user()->role === 'superadmin') {
                return redirect('/dashboardsuperadmin');
            }
            if (Auth::check() && Auth::user()->role === 'kasir') {
                return redirect('/kasir/pesanan');
            }
        }

        return $next($request);
    }
}
