<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use App\Models\AiLog;
use Illuminate\Support\Facades\Auth;

class CheckPackage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $level  (Bisa diisi 'pro' via route)
     */
    public function handle(Request $request, Closure $next, $level = null): Response
    {
        $user = $request->user();

        // 1. Validasi Dasar: Login & Status Aktif
        if (!$user || $user->status !== 'active') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif.');
        }

        // 2. Proteksi Akses Fitur PRO
        // Jika route memanggil middleware dengan parameter :pro (misal: CheckPackage:pro)
        if ($level === 'pro' && (int)$user->package === 0) {
            return redirect()->route('pricing')->with('error', 'Fitur ini khusus untuk pengguna paket PRO/Pemula.');
        }

        // 3. Logika Limit Penggunaan AI
        $limits = [
            0 => 10,   // Gratis
            1 => 100,  // Pemula
            2 => 1000, // Pro
        ];

        $userLimit = $limits[$user->package] ?? 0;

        // Cache waktu sekarang untuk efisiensi
        $now = Carbon::now();

        // Hitung penggunaan AI user bulan ini
        $aiUsage = AiLog::where('user_id', $user->id)
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        if ($aiUsage >= $userLimit) {
            $packageName = [0 => 'Gratis', 1 => 'Pemula', 2 => 'Pro'][$user->package];
            return redirect()->route('pricing')->with('error', "Limit AI Paket $packageName ($userLimit/bulan) Anda sudah habis.");
        }

        // 4. Cek Masa Aktif (Hanya untuk Paket Berbayar 1 & 2)
        if ($user->package > 0 && $user->package_expired_at) {
            // Kita parse manual untuk berjaga-jaga jika belum di-cast di Model
            $expiry = Carbon::parse($user->package_expired_at);
            
            if ($expiry->isPast()) {
                return redirect()->route('pricing')->with('error', 'Masa aktif paket Anda telah habis. Silahkan perpanjang.');
            }
        }

        return $next($request);
    }
}