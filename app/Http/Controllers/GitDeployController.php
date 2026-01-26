<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site; 
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Auth;

class GitDeployController extends Controller
{
    /**
     * Menampilkan halaman formulir deploy GitHub.
     */
    public function index(Request $request, $subdomain = null)
    {
        $user = auth()->user();
    
        // Cukup ambil subdomain & ID saja untuk dropdown
        $sites = Site::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get(['id', 'subdomain', 'repository_url']);
    
        // Mapping limit sederhana untuk validasi di view jika perlu
        $limits = [0 => 2, 1 => 10, 2 => 20];
        $limit = $limits[$user->package] ?? 2;
    
        return view('user-dashboard.deploy_github', [
            'sites' => $sites,
            'subdomain' => $subdomain, // Diambil dari route parameter
            'siteCount' => $sites->count(),
            'limit' => $limit,
        ]);
    }

    /**
     * Memproses link repository yang dikirim user.
     */
    
    public function process(Request $request)
    {
        $request->validate([
            'subdomain' => 'required|exists:sites,subdomain',
            'repository_url' => 'required|url|regex:/^https:\/\/github\.com\/.+/'
        ]);
    
        $subdomain = strtolower($request->subdomain);
        $user = Auth::user();
        
        // Cari site milik user
        $site = Site::where('subdomain', $subdomain)
                    ->where('user_id', $user->id)
                    ->firstOrFail();
    
        try {
            // Jalankan Bridge Script untuk GitHub (deploy_git.php)
            $scriptPath = base_path('server' . DIRECTORY_SEPARATOR . 'deploy_git.php');
            
            // Pastikan script tersebut ada
            if (!File::exists($scriptPath)) {
                return response()->json(['message' => 'Script deploy tidak ditemukan.'], 500);
            }
    
            $process = new Process(['php', $scriptPath, $subdomain, $request->repository_url]);
            $process->setTimeout(300); // Git clone bisa lebih lama dari unzip
            $process->run();
    
            $output = trim($process->getOutput());
    
            if (!$process->isSuccessful() || $output !== 'SUCCESS') {
                return response()->json([
                    'message' => 'Gagal menarik data dari GitHub.',
                    'debug' => $output // Optional: hapus jika sudah produksi
                ], 500);
            }
    
            // Update status dan simpan repo_url di DB
            $wwwPath = dirname(base_path()); 
            $finalPath = $wwwPath . DIRECTORY_SEPARATOR . 'users-data' . DIRECTORY_SEPARATOR . $subdomain;
    
            $site->update([
                'repository_url' => $request->repository_url,
                'folder_path' => $finalPath,
                'status' => 'active'
            ]);
    
            return response()->json(['message' => 'Deployment GitHub Berhasil!']);
    
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function sync($id)
    {
        $user = auth()->user();
        // Cari site berdasarkan ID dan pastikan milik user
        $site = Site::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        if (!$site->repository_url) {
            return response()->json(['message' => 'Situs ini tidak terhubung ke GitHub.'], 400);
        }

        try {
            $scriptPath = base_path('server' . DIRECTORY_SEPARATOR . 'deploy_git.php');
            
            // Jalankan ulang bridge script dengan repo yang sudah ada
            $process = new Process(['php', $scriptPath, $site->subdomain, $site->repository_url]);
            $process->setTimeout(300);
            $process->run();

            if (!$process->isSuccessful() || trim($process->getOutput()) !== 'SUCCESS') {
                return response()->json(['message' => 'Gagal sinkronisasi data.'], 500);
            }

            return response()->json(['message' => 'Update berhasil ditarik!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}