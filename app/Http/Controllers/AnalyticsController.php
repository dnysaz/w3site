<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\SiteStat;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function track($subdomain)
    {
        $site = Site::where('subdomain', $subdomain)->first();
        
        if ($site) {
            // 1. Update total lifetime clicks
            $site->increment('clicks_count');

            // 2. Update atau Buat statistik harian
            $stats = SiteStat::firstOrCreate(
                ['site_id' => $site->id, 'date' => now()->toDateString()],
                ['clicks' => 0]
            );
            
            $stats->increment('clicks');
        }

        // Mengembalikan pixel transparan 1x1
        $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        
        return response($pixel, 200)
            ->header('Content-Type', 'image/gif')
            // Tambahkan header cache agar browser tidak menyimpan pixel ini
            // Supaya setiap refresh tetap terhitung sebagai klik baru
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
}