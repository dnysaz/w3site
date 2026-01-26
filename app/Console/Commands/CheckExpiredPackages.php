<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckExpiredPackages extends Command
{
    protected $signature = 'packages:check-expired';
    protected $description = 'Mengembalikan user ke paket gratis jika sudah expired';

    public function handle()
    {
        // Cari user yang paketnya bukan 0 DAN tanggal expired-nya sudah lewat dari sekarang
        $expiredUsers = User::where('package', '>', 0)
                            ->where('package_expired_at', '<', now())
                            ->update([
                                'package' => 0,
                                'package_expired_at' => null
                            ]);

        $this->info("Berhasil meriset {$expiredUsers} user yang expired.");
    }
}