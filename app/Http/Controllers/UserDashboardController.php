<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use App\Models\Site;
use App\Models\Shortlink;
use App\Models\AiDesign;
use App\Models\Linktree;


class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. Ambil Data Dasar
        $sites = Site::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $linkCount = Shortlink::where('user_id', $user->id)->count();
        
        // AMBIL DATA LINKTREE
        $linktreeCount = Linktree::where('user_id', $user->id)->count();
    
        // 2. Mapping Konfigurasi Paket (Tambahkan limit Linktree)
        $packageMap = [
            0 => [
                'name' => 'Gratis', 
                'limit' => 2, 
                'storage' => 256, 
                'storage_unit' => 'Mb',
                'shortlink' => 10,
                'linktree' => 10, // Limit Linktree Gratis
                'color' => 'slate',
                'icon' => 'fa-seedling'
            ],
            1 => [
                'name' => 'Pemula', 
                'limit' => 10, 
                'storage' => 512, 
                'storage_unit' => 'Mb',
                'shortlink' => 100,
                'linktree' => 100, // Limit Linktree Pemula
                'color' => 'blue',
                'icon' => 'fa-rocket'
            ],
            2 => [
                'name' => 'Pro', 
                'limit' => 20, 
                'storage' => 1024, 
                'storage_unit' => 'Mb',
                'shortlink' => 1000,
                'linktree' => 1000, // Limit Linktree Pro
                'color' => 'indigo',
                'icon' => 'fa-briefcase'
            ],
        ];
    
        $currentPkg = $packageMap[$user->package] ?? $packageMap[0];
    
        // 3. Logika Hitung Storage SSD (Tetap sama)
        $totalSizeByte = 0;
        $storageRoot = dirname(base_path()) . DIRECTORY_SEPARATOR . 'users-data';
        foreach ($sites as $site) {
            $path = $storageRoot . DIRECTORY_SEPARATOR . $site->subdomain;
            if (File::exists($path)) {
                foreach (File::allFiles($path) as $file) { $totalSizeByte += $file->getSize(); }
            }
        }
        $totalSizeMb = round($totalSizeByte / (1024 * 1024), 2);
        
        // 4. Hitung Persentase
        $storagePercent = min(($totalSizeMb / $currentPkg['storage']) * 100, 100);
        $siteCount = $sites->count();
        $isFull = $siteCount >= $currentPkg['limit'];
        $percentUsage = ($currentPkg['limit'] > 0) ? ($siteCount / $currentPkg['limit']) * 100 : 0;
    
        $isUnlimitedLink = $currentPkg['shortlink'] > 1000;
        $linkLimitText = $isUnlimitedLink ? '∞' : $currentPkg['shortlink'];
        $linkPercent = ($isUnlimitedLink) ? 100 : ($linkCount / $currentPkg['shortlink']) * 100;
    
        // HITUNG PERSENTASE LINKTREE
        $ltLimit = $currentPkg['linktree'];
        $ltPercent = ($linktreeCount / $ltLimit) * 100;
    
        // 5. Kirim semua variabel ke View
        return view('user-dashboard.dashboard', compact(
            'user', 'siteCount', 'isUnlimitedLink', 'sites', 'linkCount', 'currentPkg', 
            'totalSizeMb', 'storagePercent', 'isFull', 'percentUsage', 'linkLimitText', 
            'linkPercent', 'linktreeCount', 'ltPercent', 'ltLimit'
        ));
    }

    public function aibuilder()
    {
        $aiStats = auth()->user()->getAiStats();

        return view('user-dashboard.aibuilder', compact('aiStats'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('user-dashboard.profile', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
    
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
        ]);
    
        $user->update([
            'name' => $validated['name']
        ]);
    
        return back()->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
     
    public function destroy(Request $request): RedirectResponse
    {
        // 1. Validasi Password
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // 2. Ambil User beserta relasi sites-nya
        $user = $request->user();
        $user->load('sites'); // Pastikan relasi 'sites' sudah didefinisikan di Model User
        $userId = $user->id;
        $scriptPath = base_path('server' . DIRECTORY_SEPARATOR . 'destroy.php');

        // 3. JALANKAN BRIDGE DESTROY UNTUK SETIAP SUBDOMAIN
        if (File::exists($scriptPath)) {
            foreach ($user->sites as $site) {
                // Gunakan $site->subdomain sesuai struktur tabel 'sites' kamu
                if (!empty($site->subdomain)) {
                    $process = new Process(['php', $scriptPath, $site->subdomain]);
                    $process->run();

                    $output = trim($process->getOutput());
                    if (!$process->isSuccessful() || $output !== 'DESTROYED') {
                        Log::error("Destroy Gagal untuk subdomain: {$site->subdomain}. Output: " . $output);
                    }
                }
            }
        }

        // 4. HAPUS STORAGE INTERNAL (Asset, Foto Profil, dll)
        $userStoragePath = 'users-designs/' . $userId;
        if (Storage::disk('public')->exists($userStoragePath)) {
            Storage::disk('public')->deleteDirectory($userStoragePath);
        }

        // 5. PROSES BREEZE (Logout & Hapus DB)
        // Database 'sites' akan otomatis terhapus karena onDelete('cascade') di migration kamu
        Auth::logout();
        $user->delete();

        // 6. INVALIDASI SESI
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Akun dan seluruh aset website Anda telah berhasil dihapus secara permanen.');
    }

    public function mysite()
    {
        $user = auth()->user();
        $sites = Site::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
    
        $linkCount = Shortlink::where('user_id', $user->id)->count();
    
        // Mapping Paket (Logika dari blok @php Anda)
        $packageMap = [
            0 => ['name' => 'Gratis', 'limit' => 2, 'color' => 'slate', 'hex' => '#64748b', 'icon' => 'fa-seedling'],
            1 => ['name' => 'Pemula', 'limit' => 10, 'color' => 'blue', 'hex' => '#2563eb', 'icon' => 'fa-rocket'],
            2 => ['name' => 'Pro', 'limit' => 20, 'color' => 'indigo', 'hex' => '#4f46e5', 'icon' => 'fa-crown'],
        ];
    
        // Ambil data paket berdasarkan kolom 'package' di database
        $currentPkg = $packageMap[$user->package] ?? $packageMap[0];
        
        // Hitung status paket
        $siteCount = $sites->count();
        $currentPkg = $packageMap[$user->package] ?? $packageMap[0];
        $limit = $currentPkg['limit'];

        // --- LOGIKA MASA AKTIF YANG DIPERBAIKI ---
        if ($user->package == 0) {
            // Jika paket 0 (Gratis)
            $expiredDate = 'Selamanya';
        } else {
            // Jika paket berbayar (1 atau 2)
            $expiredDate = $user->package_expired_at 
                ? \Carbon\Carbon::parse($user->package_expired_at)->translatedFormat('d M Y') 
                : 'Belum Aktif'; // Jika pro tapi tanggalnya kosong (safety check)
        }
        // ------------------------------------------

        $isFull = $siteCount >= $limit;
        $remaining = max(0, $limit - $siteCount);
        $percentage = ($siteCount / $limit) * 100;
    
        // Kirim semua variabel ke view
        return view('user-dashboard.mysite', compact(
            'sites', 
            'linkCount', 
            'currentPkg', 
            'limit', 
            'siteCount', 
            'isFull', 
            'remaining', 
            'percentage', 
            'expiredDate'
        ));
    }

    public function ai_magic_tools()
    {
        // Hitung jumlah desain milik user yang sedang login
        $aiDesignCount = AiDesign::where('user_id', Auth::id())->count();
    
        return view('user-dashboard.ai_page', compact('aiDesignCount'));
    }

    public function my_ai_design()
    {
        $designs = AiDesign::where('user_id', auth()->id())
                    ->latest()
                    ->get();
                    
        // Ambil data sites untuk modal deploy
        $sites = auth()->user()->sites; 

        return view('user-dashboard/my_ai_design', compact('designs', 'sites'));
    }


}