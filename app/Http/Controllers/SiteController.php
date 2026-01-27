<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\SiteStat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SiteController extends Controller
{
    /**
     * PROSES 1: Simpan Nama (Subdomain) Saja
     * Alur: Cek Kuota -> Cek Ketersediaan -> Simpan Database (Pending)
     */
    public function storeName(Request $request)
    {
        // Paksa input jadi kecil agar tidak bisa bypass (misal: "aDmIn" jadi "admin")
        $request->merge([
            'subdomain' => strtolower($request->subdomain)
        ]);

        $bannedWords = [
            // Istilah Sistem & Admin
            'admin', 'administrator', 'webmaster', 'system', 'root', 'w3site', 'official', 'dev', 
            'support', 'billing', 'help', 'mail', 'email', 'api', 'server', 'status', 'account',
            'login', 'register', 'signup', 'owner', 'staff', 'moderator','daftar','gabung','mendaftar',
            
            // Istilah Teknis / Reserved
            'www', 'ftp', 'smtp', 'pop3', 'null', 'undefined', 'index', 'home', 'blog', 'shop', 
            'store', 'asset', 'public', 'private', 'secure', 'dns', 'ns1', 'ns2', 'proxy','ssh',
        
            // JUDI ONLINE & SLOT (Indonesia & Umum)
            'judi', 'slot', 'gacor', 'jp', 'maxwin', 'zeus', 'olympus', 'bet', 'taruhan', 
            'kasino', 'casino', 'poker', 'togel', 'toto', 'slot88', ' pragmatic', 'baccarat', 
            'domino', 'qq', 'bandar', 'bola-tangkas', 'sbobet', 'link-alternatif', 'deposit-pulsa',
            'dana-gacor', 'rtp', 'rtp-live', 'jackpot', 'menang-besar', 'agen-judi', 'situs-judi','judol',
        
            // KATA KASAR & KELAMIN (Indonesia)
            'anjing', 'babi', 'monyet', 'bangsat', 'kontol', 'memek', 'jembut', 'pentil', 
            'ngentot', 'peler', 'peju', 'itil', 'pelacur', 'lonte', 'jablay', 'bejat', 
            'bencong', 'banci', 'homo', 'lesbi', 'ngewe', 'titit', 'tetek', 'toket', 
        
            // KATA KASAR & VULGAR (Inggris)
            'fuck', 'asshole', 'bitch', 'cock', 'pussy', 'dick', 'porn', 'sex', 'nude', 
            'boobs', 'vagina', 'penis', 'bastard', 'slut', 'whore', 'shits', 'cum', 'gambling'
        ];

        $request->validate([
            'subdomain' => [
                'required',
                'alpha_dash',
                'min:3',
                'max:30',
                'unique:sites,subdomain',
                Rule::notIn($bannedWords),
            ],
        ], [
            'subdomain.not_in' => 'Mohon gunakan nama situs lain!',
            'subdomain.alpha_dash' => 'Subdomain hanya boleh berisi huruf, angka, dan strip.',
            'subdomain.unique' => 'Subdomain ini sudah digunakan.',
        ]);

        $user = Auth::user();
        $subdomain = strtolower($request->subdomain);

        // Map limit berdasarkan database integer (0: Gratis, 1: Pemula, 2: Pro)
        $limits = [0 => 2, 1 => 10, 2 => 20];
        $limit = $limits[$user->package] ?? 2;

        if ($user->sites()->count() >= $limit) {
            return response()->json(['message' => 'Slot situs penuh! Silakan upgrade paket.'], 403);
        }

        // Catat di DB dengan status 'pending'
        $site = $user->sites()->create([
            'name'        => $subdomain,
            'subdomain'   => $subdomain,
            'status'      => 'pending', // Belum ada file fisik
            'environment' => 'production'
        ]);

        return response()->json([
            'message' => 'Nama situs berhasil dipesan!',
            'site_id' => $site->id
        ], 200);
    }

    /**
     * PROSES 2: Upload & Deploy File ke Nama yang Sudah Ada
     * Alur: Simpan ZIP -> Jalankan Bridge Script -> Aktifkan Status
     */
    public function uploadFile(Request $request)
    {
        $request->validate([
            'subdomain' => 'required|exists:sites,subdomain',
            'file'      => 'required|mimes:zip|max:51200', // Max 50MB
        ]);

        $subdomain = strtolower($request->subdomain);
        $site = Site::where('subdomain', $subdomain)->where('user_id', Auth::id())->firstOrFail();

        // Persiapan Path Transit
        $tempDir = base_path('server' . DIRECTORY_SEPARATOR . 'temp');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0777, true);
        }

        $fileName = time() . '_' . $subdomain . '.zip';
        $request->file('file')->move($tempDir, $fileName);
        $absoluteZipPath = $tempDir . DIRECTORY_SEPARATOR . $fileName;

        // Jalankan Bridge Script (deploy.php)
        $scriptPath = base_path('server' . DIRECTORY_SEPARATOR . 'deploy.php');
        $process = new Process(['php', $scriptPath, $subdomain, $absoluteZipPath]);
        $process->setTimeout(180);
        $process->run();

        $output = trim($process->getOutput());

        if (!$process->isSuccessful() || $output !== 'SUCCESS') {
            if (File::exists($absoluteZipPath)) File::delete($absoluteZipPath);
            return response()->json(['message' => 'Gagal deploy file.'], 500);
        }

        // Update status di DB menjadi Active
        $wwwPath = dirname(base_path()); 
        $finalPath = $wwwPath . DIRECTORY_SEPARATOR . 'users-data' . DIRECTORY_SEPARATOR . $subdomain;

        $site->update([
            'folder_path' => $finalPath,
            'status'      => 'active'
        ]);

        return response()->json(['message' => 'File berhasil dideploy!']);
    }


    public function deployFromAi(Request $request)
    {
        try {
            // 1. Validasi
            $request->validate([
                'subdomain'   => 'required|exists:sites,subdomain',
                'design_path' => 'required',
            ]);
    
            $subdomain = strtolower($request->subdomain);
            $site = Site::where('subdomain', $subdomain)->where('user_id', auth()->id())->firstOrFail();
    
            // 2. Ambil Konten HTML
            if (!Storage::disk('public')->exists($request->design_path)) {
                return response()->json(['message' => 'File desain asli tidak ditemukan di storage.'], 404);
            }
            $htmlContent = Storage::disk('public')->get($request->design_path);
    
            // 3. Persiapan Path Transit
            $tempDir = base_path('server' . DIRECTORY_SEPARATOR . 'temp');
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0777, true);
            }
    
            $fileName = 'ai_' . time() . '_' . $subdomain . '.zip';
            $absoluteZipPath = $tempDir . DIRECTORY_SEPARATOR . $fileName;
    
            // 4. BUAT ZIP (Cek apakah Class ZipArchive ada)
            if (!class_exists('ZipArchive')) {
                return response()->json(['message' => 'Ekstensi PHP ZipArchive tidak aktif di server ini.'], 500);
            }
    
            $zip = new \ZipArchive();
            if ($zip->open($absoluteZipPath, \ZipArchive::CREATE) === TRUE) {
                $zip->addFromString('index.html', $htmlContent);
                $zip->close();
            } else {
                return response()->json(['message' => 'Gagal membuat paket ZIP sementara.'], 500);
            }
    
            // 5. Jalankan Bridge Script (deploy.php)
            $scriptPath = base_path('server' . DIRECTORY_SEPARATOR . 'deploy.php');
            
            // Pastikan Symfony Process tersedia
            $process = new Process(['php', $scriptPath, $subdomain, $absoluteZipPath]);
            $process->setTimeout(180);
            $process->run();
    
            $output = trim($process->getOutput());
    
            if (!$process->isSuccessful() || $output !== 'SUCCESS') {
                return response()->json([
                    'message' => 'Script deploy gagal.',
                    'debug' => $process->getErrorOutput() ?: $output
                ], 500);
            }
    
            // 7. Update status di DB
            $wwwPath = dirname(base_path()); 
            $finalPath = $wwwPath . DIRECTORY_SEPARATOR . 'users-data' . DIRECTORY_SEPARATOR . $subdomain;
    
            $site->update([
                'folder_path' => $finalPath,
                'status'      => 'active'
            ]);
    
            return response()->json(['message' => 'Berhasil! Landing page telah terpasang.']);
    
        } catch (\Exception $e) {
            // Ini akan menangkap error apa pun dan mengirimkannya ke frontend (bukan sekadar 500 kosong)
            return response()->json([
                'message' => 'Terjadi kesalahan internal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * PROSES 3: Sesi Hancurkan (Delete)
     * Alur: Hapus data di DB -> Hapus folder fisik di server
     */
    public function destroy($id)
    {
        $site = Site::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $subdomain = $site->subdomain;

        try {
            // Jalankan bridge script khusus penghapusan (Misal: delete.php)
            // Atau jika deploy.php support mode delete, sesuaikan parameternya
            $scriptPath = base_path('server' . DIRECTORY_SEPARATOR . 'destroy.php');
            
            if (File::exists($scriptPath)) {
                $process = new Process(['php', $scriptPath, $subdomain]);
                $process->run();
            }

            // Hapus record di database
            $site->delete();

            return response()->json(['message' => 'Situs dan file berhasil dihancurkan!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus situs.'], 500);
        }
    }

    public function site_details($subdomain)
    {
        $user = auth()->user();
        
        // 1. Ambil data site
        $site = Site::where('subdomain', $subdomain)
                    ->where('user_id', $user->id)
                    ->firstOrFail();

        // 2. Hitung Storage (Tetap sama)
        $siteSizeBytes = 0;
        $storagePath = dirname(base_path()) . DIRECTORY_SEPARATOR . 'users-data' . DIRECTORY_SEPARATOR . $subdomain;
        if (File::exists($storagePath)) {
            foreach (File::allFiles($storagePath) as $file) {
                $siteSizeBytes += $file->getSize();
            }
        }
        $siteSizeMb = round($siteSizeBytes / (1024 * 1024), 2);

        // 3. Ambil Statistik Kunjungan (7 Hari Terakhir)
        $stats = SiteStat::where('site_id', $site->id)
                    ->where('date', '>=', now()->subDays(6))
                    ->orderBy('date', 'asc')
                    ->get();

        $chartLabels = [];
        $chartData = [];

        // Loop untuk memastikan setiap hari ada datanya (jika kosong diisi 0)
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('d M'); // Format: 23 Jan
            
            $stat = $stats->where('date', $date)->first();
            
            $chartLabels[] = $label;
            $chartData[] = $stat ? $stat->clicks : 0;
        }

        $isGit = !empty($site->repository_url);

        return view("user-dashboard.site_details", compact(
            'site', 
            'siteSizeMb', 
            'isGit', 
            'chartLabels', 
            'chartData'
        ));
    }
}