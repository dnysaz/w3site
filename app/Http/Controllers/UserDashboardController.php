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

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $sites = Site::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
    
        // Fixed limit: 512MB
        $storageLimitMb = 512;

        // Logika Hitung Storage SSD
        $totalSizeByte = 0;
        $storageRoot = dirname(base_path()) . DIRECTORY_SEPARATOR . 'users-data';
        foreach ($sites as $site) {
            $path = $storageRoot . DIRECTORY_SEPARATOR . $site->subdomain;
            if (File::exists($path)) {
                foreach (File::allFiles($path) as $file) { $totalSizeByte += $file->getSize(); }
            }
        }
        $totalSizeMb = round($totalSizeByte / (1024 * 1024), 2);
        
        // Hitung Persentase Storage
        $storagePercent = min(($totalSizeMb / $storageLimitMb) * 100, 100);
        $siteCount = $sites->count();
    
        // Kirim ke View
        return view('user-dashboard.dashboard', compact(
            'user', 'siteCount', 'sites', 
            'totalSizeMb', 'storagePercent'
        ));
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

    public function mysite()
    {
        $user = auth()->user();
        $sites = Site::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
        $siteCount = $sites->count();
        $expiredDate = 'Forever';
    
        // Kirim semua variabel ke view
        return view('user-dashboard.mysite', compact(
            'sites', 
            'siteCount', 
            'expiredDate'
        ));
    }



    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Validasi Password (Hanya jika user BUKAN dari Google)
        if ($user->social_type !== 'google') {
            $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]);
        }

        // 2. Ambil User beserta relasi sites-nya
        $user->load('sites'); 
        $userId = $user->id;
        $scriptPath = base_path('server' . DIRECTORY_SEPARATOR . 'destroy.php');

        // 3. JALANKAN BRIDGE DESTROY UNTUK SETIAP SUBDOMAIN
        if (File::exists($scriptPath)) {
            foreach ($user->sites as $site) {
                if (!empty($site->subdomain)) {
                    // Menjalankan script PHP eksternal untuk menghapus file di server
                    $process = new Process(['php', $scriptPath, $site->subdomain]);
                    $process->run();

                    $output = trim($process->getOutput());
                    if (!$process->isSuccessful() || $output !== 'DESTROYED') {
                        Log::error("Destroy failed for subdomain: {$site->subdomain}. Output: " . $output);
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
        Auth::logout();
        $user->delete();

        // 6. INVALIDASI SESI
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account and all website assets have been permanently deleted.');
    }


}