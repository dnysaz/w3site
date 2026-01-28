<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlyAdminCanSeeLogs
{
    public function handle(Request $request, Closure $next): Response
    {
        // Daftar email yang diizinkan
        $allowedEmails = ['hello@w3site.id', 'admin@w3site.id'];

        // Cek apakah email user ada di dalam daftar tersebut
        if ($request->user() && in_array($request->user()->email, $allowedEmails)) {
            return $next($request);
        }

        abort(404);
    }
}