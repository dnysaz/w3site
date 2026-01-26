<?php
/**
 * BRIDGE: SESI HANCURKAN (Dinamis www/)
 * Menghapus folder subdomain secara fisik dan permanen
 */

$subdomain = $argv[1] ?? null;
$logFile = __DIR__ . '/destroy.log';

if (!$subdomain) {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - ERROR: Subdomain argument missing\n", FILE_APPEND);
    exit("ERROR: Subdomain tidak disebut");
}

// 1. Tentukan Path Dinamis
$serverDir  = __DIR__;
$projectDir = dirname($serverDir); 
$wwwPath    = dirname($projectDir); 

$baseDir    = $wwwPath . DIRECTORY_SEPARATOR . 'users-data';
$targetDir  = $baseDir . DIRECTORY_SEPARATOR . basename($subdomain);

// 2. Eksekusi Penghancuran (rm -rf)
// Di macOS/Linux, rm -rf adalah cara tercepat menghapus folder beserta isinya
if (is_dir($targetDir) && strlen($subdomain) > 2) {
    // Gunakan full path ke /bin/rm untuk reliabilitas
    $command = "/bin/rm -rf " . escapeshellarg($targetDir) . " 2>&1";
    $output = shell_exec($command);
    
    // Verifikasi Akhir
    if (!is_dir($targetDir)) {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - SUCCESS: $targetDir deleted\n", FILE_APPEND);
        echo "DESTROYED";
    } else {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - ERROR: Failed to delete. Output: $output\n", FILE_APPEND);
        echo "ERROR_DELETE_FAILED";
    }
} else {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - ERROR: Target not found: $targetDir\n", FILE_APPEND);
    echo "ERROR_NOT_FOUND";
}