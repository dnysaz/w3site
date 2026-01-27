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

if (!file_exists($pendingTemplate)) {
    exit("ERROR: File template pending tidak ditemukan.");
}

try {
    $mainIndexPath = $targetDir . DIRECTORY_SEPARATOR . 'index.html';
    $userIndices = ['index.html', 'index.htm'];

    if ($action === 'pending') {
        // --- 1. AMANKAN FILE ASLI DULU ---
        foreach ($userIndices as $file) {
            $filePath = $targetDir . DIRECTORY_SEPARATOR . $file;
            // Pastikan kita tidak me-rename file yang sudah di-lock atau file pending itu sendiri
            if (file_exists($filePath)) {
                // Rename menjadi .locked (Misal: index.html -> index.html.locked)
                rename($filePath, $filePath . '.locked');
            }
        }

        // --- 2. PASANG FILE PENDING ---
        // Setelah yang asli aman jadi .locked, baru kita copy template ke index.html
        copy($pendingTemplate, $mainIndexPath);
        
        echo "SUCCESS: Status set to Pending. Original files locked.";
    } 
    else if ($action === 'active') {
        // --- 1. HAPUS FILE PENDING ---
        if (file_exists($mainIndexPath)) {
            unlink($mainIndexPath);
        }

        // --- 2. KEMBALIKAN FILE ASLI ---
        foreach ($userIndices as $file) {
            $lockedFile = $targetDir . DIRECTORY_SEPARATOR . $file . '.locked';
            if (file_exists($lockedFile)) {
                // Kembalikan ke nama semula (Misal: index.html.locked -> index.html)
                rename($lockedFile, $targetDir . DIRECTORY_SEPARATOR . $file);
            }
        }
        echo "SUCCESS: Status set to Active. Original files restored.";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}