<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


class EnvEditorController extends Controller
{
    protected $envPath;

    public function __construct() {
        $this->envPath = base_path('.env');
    }

    public function index() {
        if (!File::exists($this->envPath)) {
            return back()->with('error', 'File .env tidak ditemukan.');
        }
        
        $content = File::get($this->envPath);
        return view('admin-dashboard.env.index', compact('content'));
    }

    public function update(Request $request) {
        $request->validate([
            'content' => 'required'
        ]);

        try {
            // 1. Cek izin tulis (Writability)
            if (!File::isWritable($this->envPath)) {
                return back()->with('error', 'File .env tidak diizinkan untuk diedit (Permission Denied).');
            }

            // 2. Backup Otomatis ke Folder Storage
            $backupDir = storage_path('app/env_backups');
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }
            File::copy($this->envPath, $backupDir . '/.env.bak_' . date('Ymd_His'));
            
            // 3. Simpan konten baru
            File::put($this->envPath, $request->content);

            // 4. Catat Log (Untuk Keamanan Audit)
            Log::info('File .env telah diupdate oleh Admin ID: ' . auth()->id());

            // 5. REDIRECT DULU, baru kasih instruksi clear cache
            // Kita hindari Artisan::call di sini agar tidak kena ERR_EMPTY_RESPONSE
            return redirect()->route('admin.env.index')->with('success', 'File .env berhasil diperbarui! Silakan klik tombol "Optimalkan Sistem" jika perubahan belum terasa.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update file: ' . $e->getMessage());
        }
    }

    /**
     * Route terpisah untuk clear cache agar tidak bentrok saat simpan file
     */
    public function clearCache() {
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            
            return back()->with('success', 'Cache sistem berhasil dibersihkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal bersihkan cache: ' . $e->getMessage());
        }
    }


    public function backupDatabase()
    {
        try {
            $connection = config('database.default');
    
            // LOGIKA UNTUK SQLITE (LOKAL)
            if ($connection === 'sqlite') {
                $sqlitePath = config('database.connections.sqlite.database');
                
                if (!File::exists($sqlitePath)) {
                    return back()->with('error', 'File database SQLite tidak ditemukan.');
                }
    
                $filename = "backup-local-" . date('Y-m-d_H-i-s') . ".sqlite";
                return response()->download($sqlitePath, $filename);
            }
    
            // LOGIKA UNTUK POSTGRESQL (VPS)
            if ($connection === 'pgsql') {
                $filename = "backup-db-" . date('Y-m-d_H-i-s') . ".sql";
                $path = storage_path("app/db_backups/" . $filename);

                if (!File::exists(storage_path("app/db_backups"))) {
                    File::makeDirectory(storage_path("app/db_backups"), 0755, true);
                }
                
                // Gunakan full path hasil 'which pg_dump' tadi
                $pgDumpPath = '/usr/bin/pg_dump'; 

                $process = Process::fromShellCommandline(sprintf(
                    'PGPASSWORD="%s" %s -h %s -U %s -d %s > %s',
                    config('database.connections.pgsql.password'),
                    $pgDumpPath, // Jalur absolut
                    config('database.connections.pgsql.host'),
                    config('database.connections.pgsql.username'),
                    config('database.connections.pgsql.database'),
                    $path
                ));

                $process->setTimeout(300); 
                $process->run();

                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                return response()->download($path)->deleteFileAfterSend(true);
            }
    
            return back()->with('error', 'Driver database tidak didukung untuk backup otomatis.');
    
        } catch (\Exception $e) {
            Log::error('Backup Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat backup.');
        }
    }
}