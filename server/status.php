<?php
/**
 * BRIDGE: TOGGLE STATUS (ANTI-BYPASS)
 * File: server/status.php
 */

// Bersihkan cache status file sistem agar PHP tidak membaca data lama
clearstatcache();

$subdomain = $argv[1] ?? null;
$action = $argv[2] ?? null; 

if (!$subdomain || !$action) {
    exit("ERROR: Argumen tidak lengkap.");
}

$wwwPath = dirname(dirname(__DIR__));
$targetDir = $wwwPath . DIRECTORY_SEPARATOR . 'users-data' . DIRECTORY_SEPARATOR . $subdomain;
$pendingTemplate = __DIR__ . DIRECTORY_SEPARATOR . 'index_pending.html';

if (!is_dir($targetDir)) {
    exit("ERROR: Folder website tidak ditemukan.");
}

try {
    $userIndices = ['index.html', 'index.htm'];

    if ($action === 'pending') {
        if (!file_exists($pendingTemplate)) {
            exit("ERROR: File template pending tidak ditemukan.");
        }

        // 1. Amankan file asli ke .locked
        foreach ($userIndices as $file) {
            $filePath = $targetDir . DIRECTORY_SEPARATOR . $file;
            // Jika index.html ada dan BUKAN file .locked, segera rename
            if (file_exists($filePath)) {
                // Pastikan kita tidak me-rename file yang sebenarnya sudah dipending sebelumnya
                rename($filePath, $filePath . '.locked');
            }
        }

        // 2. Pasang file pending
        copy($pendingTemplate, $targetDir . DIRECTORY_SEPARATOR . 'index.html');
        
        echo "SUCCESS";
    } 
    
    else if ($action === 'active') {
        // 1. Hapus file "Pending/Gembok" yang sedang tampil saat ini
        foreach ($userIndices as $file) {
            $currentFile = $targetDir . DIRECTORY_SEPARATOR . $file;
            // Hapus jika file itu ada dan TIDAK ada file .locked-nya (berarti ini file sampah/pending)
            // ATAU hapus saja jika file .locked-nya tersedia untuk dikembalikan
            if (file_exists($currentFile)) {
                unlink($currentFile);
            }
        }

        // 2. Kembalikan file asli dari .locked
        $restored = false;
        foreach ($userIndices as $file) {
            $lockedFile = $targetDir . DIRECTORY_SEPARATOR . $file . '.locked';
            $originalFile = $targetDir . DIRECTORY_SEPARATOR . $file;

            if (file_exists($lockedFile)) {
                // Pastikan tujuan (index.html) sudah benar-benar kosong sebelum rename
                if (!file_exists($originalFile)) {
                    rename($lockedFile, $originalFile);
                    $restored = true;
                }
            }
        }
        
        echo "SUCCESS";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}