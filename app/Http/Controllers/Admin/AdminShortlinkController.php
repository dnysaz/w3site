<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shortlink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminShortlinkController extends Controller
{
    public function index()
    {
        // 1. Ambil data dengan paginasi
        $shortlinks = Shortlink::with('user')->latest()->paginate(15);

        // 2. Data untuk Grafik (Looping 7 hari terakhir agar data konsisten)
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->translatedFormat('d M'); 
            
            // Hitung jumlah shortlink per hari
            $chartData[] = Shortlink::whereDate('created_at', $date->toDateString())->count();
        }

        // 3. Statistik Ringkas
        $stats = [
            'total_shortlinks' => Shortlink::count(),
            // DISINKRONKAN: Menggunakan 'clicks' sesuai migrasi terbaru
            'total_clicks'     => Shortlink::sum('clicks'), 
        ];

        return view('admin-dashboard.shortlinks.index', compact(
            'shortlinks', 
            'chartLabels', 
            'chartData', 
            'stats'
        ));
    }

    /**
     * Toggle Status Shortlink (Active/Disabled)
     */
    public function toggle($id)
    {
        $shortlink = Shortlink::findOrFail($id);
        
        // DISINKRONKAN: Menggunakan 'is_active' sesuai migrasi terbaru
        $shortlink->is_active = !$shortlink->is_active;
        $shortlink->save();

        $statusMessage = $shortlink->is_active ? 'diaktifkan kembali' : 'dinonaktifkan';

        // DISINKRONKAN: Menggunakan 'slug' untuk pesan sukses
        return back()->with('success', "Shortlink w3site.id/{$shortlink->slug} berhasil {$statusMessage}.");
    }
}