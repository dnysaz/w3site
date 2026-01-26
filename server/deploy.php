<?php
/**
 * BRIDGE: DEPLOY SUBDOMAIN
 * Acuan: Folder www/
 */

$subdomain = $argv[1] ?? null;
$zipPath = $argv[2] ?? null; 

// 1. Validasi Awal
if (!$subdomain || !$zipPath) {
    exit("ERROR: Argumen tidak lengkap.");
}

// Gunakan realpath untuk memastikan path ZIP terbaca sistem Mac/Linux
$realZipPath = realpath($zipPath);

if (!$realZipPath || !file_exists($realZipPath)) {
    exit("ERROR: File zip tidak ditemukan di path: " . $zipPath);
}

// 2. Tentukan Path Target (www/users-data/subdomain)
// Script ini ada di www/w3site.id/server/deploy.php
// dirname(__DIR__) => www/w3site.id
// dirname(dirname(__DIR__)) => www
$wwwPath = dirname(dirname(__DIR__));
$baseDir = $wwwPath . DIRECTORY_SEPARATOR . 'users-data';
$targetDir = $baseDir . DIRECTORY_SEPARATOR . basename($subdomain);

// 3. Eksekusi Folder & Ekstraksi
try {
    // Pastikan folder users-data ada
    if (!is_dir($baseDir)) {
        mkdir($baseDir, 0775, true);
    }

    // Jika folder target sudah ada, hapus/timpa (untuk proses update)
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0775, true);
    }

    // Ekstrak menggunakan ZipArchive
    if (class_exists('ZipArchive')) {
        $zip = new ZipArchive;
        if ($zip->open($realZipPath) === TRUE) {
            $zip->extractTo($targetDir);
            $zip->close();

            // Tambahkan ini di deploy.php setelah extractTo
            $macosxFolder = $targetDir . DIRECTORY_SEPARATOR . '__MACOSX';
            if (is_dir($macosxFolder)) {
                // Panggil fungsi deleteDirectory atau exec hapus
                exec("rm -rf " . escapeshellarg($macosxFolder));
            }
            
            // Hapus file ZIP sementara setelah sukses
            unlink($realZipPath);
            echo "SUCCESS";
        } else {
            echo "ERROR: Gagal membuka ZIP. Code: file mungkin korup.";
        }
    } else {
        echo "ERROR: Ekstensi ZipArchive tidak aktif di PHP server ini.";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}