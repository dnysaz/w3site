<?php
/**
 * BRIDGE: TOGGLE STATUS (ANTI-BYPASS)
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

try {
    $gembokPath = $targetDir . DIRECTORY_SEPARATOR . 'index.html';
    // Kita cari semua kemungkinan file index milik user
    $userIndices = ['index.html', 'index.htm'];

    if ($action === 'pending') {
        // 1. "Gembok" pintu utama
        copy($pendingTemplate, $gembokPath);

        // 2. "Sembunyikan" file asli agar tidak bisa dipanggil via URL manual
        foreach ($userIndices as $file) {
            $filePath = $targetDir . DIRECTORY_SEPARATOR . $file;
            if (file_exists($filePath)) {
                // Rename index.html -> index.html.locked
                rename($filePath, $filePath . '.locked');
            }
        }
        echo "SUCCESS";
    } 
    else if ($action === 'active') {
        // 1. Buang gemboknya
        if (file_exists($gembokPath)) {
            unlink($gembokPath);
        }

        // 2. Kembalikan file asli dari persembunyian
        foreach ($userIndices as $file) {
            $lockedFile = $targetDir . DIRECTORY_SEPARATOR . $file . '.locked';
            if (file_exists($lockedFile)) {
                // Rename index.html.locked -> index.html
                rename($lockedFile, str_replace('.locked', '', $lockedFile));
            }
        }
        echo "SUCCESS";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}