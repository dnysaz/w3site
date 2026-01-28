<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TerminalController extends Controller
{
    // Konfigurasi Email Admin yang diizinkan
    protected $allowedEmails = [
        'admin@w3site.id',
        'hello@w3site.id'
    ];

    // Konfigurasi Folder Kerja Utama
    protected $workDir = '/var/www';

    /**
     * Tampilkan Halaman Terminal
     */
    public function index()
    {
        if (!in_array(Auth::user()->email, $this->allowedEmails)) {
            Log::warning("Percobaan akses halaman terminal ilegal oleh: " . Auth::user()->email);
            abort(403, 'Akses Terbatas! Anda tidak memiliki izin untuk mengakses Terminal.');
        }

        return view('admin-dashboard.terminal.index');
    }

    /**
     * Eksekusi Perintah Terminal via AJAX
     */
    public function execute(Request $request)
    {
        // 1. Validasi Akses (Proteksi Lapis Kedua)
        if (!in_array(Auth::user()->email, $this->allowedEmails)) {
            Log::alert("Percobaan eksekusi perintah terminal ilegal via API oleh: " . Auth::user()->email);
            return response()->json(['output' => 'Unauthorized Access.', 'status' => 'error'], 403);
        }

        $command = $request->command;

        // 2. Validasi Keamanan: Daftar Hitam Perintah (Blacklist)
        $blacklist = [
            'rm', 'rf', 'chmod', 'chown', 'sudo', 'su ', 'passwd', 
            'mkfs', 'dd', 'shutdown', 'reboot', ':', 'mv /', 'fdisk',
            'apt', 'yum', 'dnf', 'ssh', 'scp', 'wget', 'curl -O'
        ];

        foreach ($blacklist as $bad) {
            if (stripos($command, $bad) !== false) {
                return response()->json([
                    'output' => "⚠️ ERROR: Perintah '$bad' dilarang demi keamanan sistem!",
                    'status' => 'error'
                ]);
            }
        }

        // 3. Persiapan Eksekusi
        // Memaksa setiap perintah dimulai dari direktori yang ditentukan
        $fullCommand = "cd {$this->workDir} && $command";
        
        $process = Process::fromShellCommandline($fullCommand);
        $process->setWorkingDirectory($this->workDir);
        $process->setTimeout(300); // Batas waktu 5 menit

        try {
            $process->run();
            
            // Ambil output standar, jika kosong ambil error output (seperti pesan 'command not found')
            $output = $process->getOutput();
            $errorOutput = $process->getErrorOutput();
            
            $finalOutput = $output ?: $errorOutput;

            return response()->json([
                'output' => $finalOutput ?: 'Command executed (no output)',
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'output' => 'System Error: ' . $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }
}