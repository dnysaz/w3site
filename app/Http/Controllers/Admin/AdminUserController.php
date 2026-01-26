<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;


class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
    
        $users = User::when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Penting agar pencarian tidak hilang saat pindah halaman (pagination)
    
        $packageStats = [
            'gratis' => User::where('package', 0)->count(),
            'pemula' => User::where('package', 1)->count(),
            'pro'    => User::where('package', 2)->count(),
        ];
    
        return view('admin-dashboard.users.index', compact('users', 'packageStats'));
    }

    public function updatePackage(Request $request, $id) {
        $user = User::findOrFail($id);
        $user->update([
            'package' => $request->package,
            'package_expired_at' => $request->package == 0 ? null : now()->addYear()
        ]);
        return back()->with('success', 'Paket user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // 1. Ambil User beserta data sitenya sebelum dihapus
        $user = User::with('sites')->findOrFail($id);
        $scriptPath = base_path('server' . DIRECTORY_SEPARATOR . 'destroy.php');

        // 2. Loop semua subdomain milik user dan panggil bridge script
        foreach ($user->sites as $site) {
            // Jalankan destroy.php untuk setiap subdomain
            $process = new Process(['php', $scriptPath, $site->subdomain]);
            $process->run();
            
            // Kita tidak menghentikan proses jika gagal hapus folder (agar DB tetap bersih),
            // tapi kita catat log jika perlu (opsional).
        }

        // 3. Hapus User dari Database
        // Karena migration kamu memakai ->onDelete('cascade'), 
        // semua record di tabel 'sites' milik user ini akan otomatis hilang.
        $user->delete();

        return back()->with('success', 'User dan seluruh folder website miliknya telah dimusnahkan.');
    }
}