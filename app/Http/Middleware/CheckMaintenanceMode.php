<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah fitur maintenance dinyalakan (bisa lewat .env atau database)
        // $isMaintenance = config('app.dashboard_maintenance', false);
        $isMaintenance = cache('dashboard_maintenance', false);

        // Jika maintenance AKTIF dan user mencoba akses /dashboard atau /login
        if ($isMaintenance && ($request->is('dashboard*') || $request->is('admin*') || $request->is('login'))) {
            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
}
