<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class TerminalController extends Controller
{
    protected $allowedEmails = [
        'admin@w3site.id',
        'hello@w3site.id'
    ];

    // Default folder dijatuhkan langsung ke w3site
    protected $baseDir = '/var/www/w3site';

    public function index()
    {
        if (!in_array(Auth::user()->email, $this->allowedEmails)) {
            abort(403);
        }

        // Set lokasi awal ke w3site saat halaman dimuat
        Session::put('terminal_cwd', $this->baseDir);

        return view('admin-dashboard.terminal.index');
    }

    public function execute(Request $request)
    {
        if (!in_array(Auth::user()->email, $this->allowedEmails)) {
            return response()->json(['output' => 'Unauthorized.', 'status' => 'error'], 403);
        }

        $command = trim($request->command);
        
        // Ambil folder terakhir dari session, jika tidak ada pakai default w3site
        $currentDir = Session::get('terminal_cwd', $this->baseDir);

        // Proteksi Blacklist (ditambah sedikit lebih ketat)
        $blacklist = ['rm ', 'sudo ', 'chmod ', 'chown ', 'passwd', 'shutdown', 'reboot', 'nano', 'vi ','rf'];
        foreach ($blacklist as $bad) {
            if (stripos($command, $bad) !== false) {
                return response()->json(['output' => "⚠️ Akses ditolak: Perintah dilarang.", 'status' => 'error']);
            }
        }

        // --- LOGIKA NAVIGASI (CD) ---
        if (preg_match('/^cd\s+(.+)$/', $command, $matches)) {
            $targetDir = $matches[1];
            
            // Perintah untuk cek apakah folder tujuan valid dan ambil path absolutnya
            $testCommand = "cd $currentDir && cd $targetDir && pwd";
            $process = Process::fromShellCommandline($testCommand);
            $process->run();

            if ($process->isSuccessful()) {
                $newPath = trim($process->getOutput());
                
                // Simpan path baru ke session agar perintah selanjutnya berjalan di sini
                Session::put('terminal_cwd', $newPath);
                
                // Berikan feedback visual ke user
                $displayPath = str_replace($this->baseDir, '~', $newPath);
                return response()->json([
                    'output' => "Navigated to: $displayPath",
                    'current_dir' => $newPath,
                    'status' => 'success'
                ]);
            } else {
                return response()->json(['output' => "bash: cd: $targetDir: No such file or directory", 'status' => 'error']);
            }
        }

        // --- EKSEKUSI PERINTAH UMUM ---
        // Menjalankan perintah dengan selalu masuk ke folder terakhir terlebih dahulu
        $fullCommand = "cd $currentDir && $command";
        $process = Process::fromShellCommandline($fullCommand);
        $process->setTimeout(300);

        try {
            $process->run();
            $output = $process->getOutput();
            $error = $process->getErrorOutput();

            return response()->json([
                'output' => $output ?: ($error ?: 'Done.'),
                'current_dir' => $currentDir,
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json(['output' => 'System Error: ' . $e->getMessage(), 'status' => 'error']);
        }
    }
}