<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Site;
use App\Models\Linktree;
use App\Models\Shortlink;
use App\Models\Transaction;
use App\Models\VisitLog; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama (Cards)
        $stats = [
            'total_users'      => User::count() ?: 0,
            'total_revenue'    => Transaction::whereIn('transaction_status', ['settlement', 'capture'])->sum('amount') ?: 0,
            'total_sites'      => Site::count() ?: 0,
            'total_linktrees'  => Linktree::count() ?: 0,
            'total_shortlinks' => Shortlink::count() ?: 0,
            'total_visits'     => VisitLog::count() ?: 0,
        ];

        // 2. Data Grafik Pendaftaran User (7 Hari Terakhir)
        $chartData = collect(); 
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartData->push((object)[
                'date'  => $date->translatedFormat('d M'),
                'total' => User::whereDate('created_at', $date->toDateString())->count()
            ]);
        }

        // 3. Data Grafik Traffic Kunjungan (7 Hari Terakhir)
        $visitorChartData = collect();
        foreach ($chartData as $data) {
            // Kita parse kembali tanggalnya untuk filter di VisitLog
            $currentDate = Carbon::createFromFormat('d M', $data->date)->year(now()->year)->toDateString();
            
            $visitorChartData->push((object)[
                'date'  => $data->date,
                'total' => VisitLog::whereDate('date', $currentDate)->count()
            ]);
        }

        // 4. Statistik Doughnut Charts
        $userStats = [
            'gratis' => User::where('package', 0)->count(),
            'pemula' => User::where('package', 1)->count(),
            'pro'    => User::where('package', 2)->count(),
        ];

        $siteStats = [
            'file'   => Site::where(function($q) {
                            $q->whereNull('repository_url')->orWhere('repository_url', '');
                        })->count(),
            'github' => Site::whereNotNull('repository_url')->where('repository_url', '!=', '')->count(),
        ];

        // 5. Data Ranking Traffic (Negara & Browser)
        $topCountries = VisitLog::select('country', DB::raw('count(*) as total'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        $topBrowsers = VisitLog::select('browser', DB::raw('count(*) as total'))
            ->groupBy('browser')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // 6. Data List Terbaru (Transaksi & Kunjungan)
        $latest_transactions = Transaction::with('user')
            ->whereIn('transaction_status', ['settlement', 'capture', 'success'])
            ->latest()
            ->take(5)
            ->get();

        $latest_visits = VisitLog::latest()->take(5)->get();

        return view('admin-dashboard.dashboard', compact(
            'stats', 
            'userStats', 
            'siteStats', 
            'chartData', 
            'visitorChartData',
            'topCountries', 
            'topBrowsers', 
            'latest_transactions',
            'latest_visits'
        ));
    }
}