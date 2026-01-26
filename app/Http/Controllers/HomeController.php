<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\VisitLog; 
use Jenssegers\Agent\Agent; // Tambahkan ini
use Stevebauman\Location\Facades\Location; // Tambahkan ini

class HomeController extends Controller
{
    public function index()
    {
        // 1. Logika Pencatatan Pengunjung
        $this->logVisit();

        // 2. Ambil data Site terbaru (Logika asli kamu)
        $latestSites = Site::latest()
                        ->take(10)
                        ->get();
    
        return view('welcome', compact('latestSites'));
    }

    /**
     * Fungsi Privat untuk mencatat log kunjungan
     */
    private function logVisit()
    {
        // Hanya catat jika user belum berkunjung dalam sesi ini
        if (!session()->has('has_visited_today')) {
            $agent = new Agent();
            $ip = request()->ip();
            $loc = Location::get($ip == '127.0.0.1' ? '8.8.8.8' : $ip);

            VisitLog::create([
                'date'       => now()->toDateString(),
                'ip_address' => $ip,
                'browser'    => $agent->browser(),
                'platform'   => $agent->platform(),
                'country'    => $loc->countryName ?? 'Unknown',
                'city'       => $loc->cityName ?? 'Unknown',
            ]);

            session()->put('has_visited_today', true);
        }
    }
}