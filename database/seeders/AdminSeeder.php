<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@w3site.id'], // Mencegah duplikasi jika seeder dijalankan ulang
            [
                'name' => 'admin_w3site',
                'password' => Hash::make('Lemarikaca01#'),
                'package' => 2, // Paket Pro
                'package_expired_at' => Carbon::now()->addYears(100), // Berlangsung sangat lama
                'email_verified_at' => Carbon::now(), // Langsung terverifikasi
                'role' => 'admin', // Menggunakan kolom role sesuai permintaan kamu
            ]
        );

        $this->command->info('Akun Admin Super (Pro & Verified) berhasil dibuat!');
    }
}