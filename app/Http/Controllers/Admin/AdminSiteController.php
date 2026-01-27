<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class AdminSiteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $sites = Site::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where('subdomain', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $siteStats = [
            // Mendeteksi ZIP jika repository_url kosong
            'zip' => Site::where(function($q) {
                $q->whereNull('repository_url')->orWhere('repository_url', '');
            })->count(),
            // Mendeteksi GitHub jika repository_url terisi
            'github' => Site::whereNotNull('repository_url')->where('repository_url', '!=', '')->count(),
        ];

        return view('admin-dashboard.sites.index', compact('sites', 'siteStats'));
    }

    public function toggleStatus($id)
    {
        $site = Site::findOrFail($id);
        $newStatus = ($site->status == 'active') ? 'pending' : 'active';

        $scriptPath = base_path('server' . DIRECTORY_SEPARATOR . 'status.php');
        
        // Gunakan Process untuk menjalankan script
        $process = new Process(['php', $scriptPath, $site->subdomain, $newStatus]);
        $process->setTimeout(60);
        $process->run();

        // Ambil output standar (SUCCESS) dan error output (jika ada ERROR dari script)
        $output = trim($process->getOutput());
        $errorOutput = trim($process->getErrorOutput());

        // Cek apakah output mengandung kata SUCCESS
        if (!$process->isSuccessful() || !str_contains($output, 'SUCCESS')) {
            // Jika gagal, tampilkan pesan error yang dikirim oleh script PHP bridge
            $errorMessage = $errorOutput ?: $output;
            return back()->with('error', 'Gagal di server: ' . ($errorMessage ?: 'Unknown Error'));
        }

        // Update status di Database jika server sukses
        $site->update(['status' => $newStatus]);

        $msg = $newStatus == 'active' ? 'Website Aktif Kembali' : 'Website Berhasil Disuspensi';
        return back()->with('success', $msg);
    }
}