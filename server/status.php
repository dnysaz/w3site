<?php
/**
 * BRIDGE: TOGGLE STATUS (ANTI-BYPASS)
 * File: server/status.php
 */

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

if (!file_exists($pendingTemplate)) {
    exit("ERROR: File template pending tidak ditemukan.");
}

try {
    $userIndices = ['index.html', 'index.htm'];

    if ($action === 'pending') {
        // 1. Sembunyikan file asli (index.html -> index.html.locked)
        foreach ($userIndices as $file) {
            $filePath = $targetDir . DIRECTORY_SEPARATOR . $file;
            if (file_exists($filePath) && !str_ends_with($filePath, '.locked')) {
                rename($filePath, $filePath . '.locked');
            }
        }

        // 2. Pasang file pending (copy dari template)
        copy($pendingTemplate, $targetDir . DIRECTORY_SEPARATOR . 'index.html');
        
        // Output ringkas agar mudah dibaca Laravel
        echo "SUCCESS";
    } 
    else if ($action === 'active') {
        // 1. Hapus SEMUA file index yang aktif (membersihkan file pending/gembok)
        foreach ($userIndices as $file) {
            $filePath = $targetDir . DIRECTORY_SEPARATOR . $file;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // 2. Kembalikan file asli (index.html.locked -> index.html)
        $restored = false;
        foreach ($userIndices as $file) {
            $lockedFile = $targetDir . DIRECTORY_SEPARATOR . $file . '.locked';
            if (file_exists($lockedFile)) {
                rename($lockedFile, $targetDir . DIRECTORY_SEPARATOR . $file);
                $restored = true;
            }
        }
        
        echo "SUCCESS";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}