<?php
/**
 * BRIDGE: DEPLOY SUBDOMAIN VIA GITHUB
 * Acuan: Folder www/
 */

$subdomain = $argv[1] ?? null;
$repoUrl = $argv[2] ?? null; 

// 1. Validasi Awal
if (!$subdomain || !$repoUrl) {
    exit("ERROR: Argumen tidak lengkap.");
}

// 2. Tentukan Path Target (www/users-data/subdomain)
// Script ini ada di www/w3site.id/server/deploy_git.php
$wwwPath = dirname(dirname(__DIR__));
$baseDir = $wwwPath . DIRECTORY_SEPARATOR . 'users-data';
$targetDir = $baseDir . DIRECTORY_SEPARATOR . basename($subdomain);

try {
    // Pastikan folder base (users-data) ada
    if (!is_dir($baseDir)) {
        mkdir($baseDir, 0775, true);
    }

    // Pastikan folder target ada
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0775, true);
    }

    // 3. Bersihkan konten lama (penting agar tidak terjadi konflik file ZIP vs Git)
    // Kita gunakan perintah shell agar cepat menghapus file hidden (.git, .env, dll)
    exec("rm -rf " . escapeshellarg($targetDir) . DIRECTORY_SEPARATOR . "*");
    exec("rm -rf " . escapeshellarg($targetDir) . DIRECTORY_SEPARATOR . ".* 2>/dev/null");

    // 4. Eksekusi Git Clone
    // Triknya: Tambahkan simbol titik (.) di akhir agar git clone ke folder saat ini (root), 
    // bukan membuat sub-folder baru.
    $cmd = "git clone --depth 1 " . escapeshellarg($repoUrl) . " " . escapeshellarg($targetDir) . " 2>&1";
    exec($cmd, $output, $returnVar);

    if ($returnVar === 0) {
        // 5. Hapus folder .git untuk keamanan agar tidak bisa di-scan orang
        $gitFolder = $targetDir . DIRECTORY_SEPARATOR . '.git';
        if (is_dir($gitFolder)) {
            exec("rm -rf " . escapeshellarg($gitFolder));
        }
        
        echo "SUCCESS";
    } else {
        echo "ERROR: Git gagal menarik data. " . implode(" ", $output);
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}