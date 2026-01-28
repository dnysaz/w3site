<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class LogSystemController extends Controller
{
    public function index()
    {
        $path = storage_path('logs/laravel.log');
        $content = File::exists($path) ? File::get($path) : 'Belum ada catatan log.';

        // Kita balik supaya log terbaru ada di paling atas
        $lines = array_reverse(explode("\n", trim($content)));
        $logData = implode("\n", array_slice($lines, 0, 500)); // Ambil 500 baris terbaru

        return view('admin-dashboard.logs.index', compact('logData'));
    }

    public function clear()
    {
        $path = storage_path('logs/laravel.log');
        if (File::exists($path)) {
            File::put($path, ''); // Kosongkan file
        }
        return back()->with('success', 'Log berhasil dibersihkan!');
    }
}