<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserPribadiSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'danayasa2@gmail.com'],
            [
                'name' => 'Ketut Dana',
                'password' => Hash::make('Lemarikaca01'),
                'role' => 'user', // Sesuai permintaan: role user
                'package' => 2,   // 2 = Pro
                'package_expired_at' => Carbon::now()->addYears(10),
                'email_verified_at' => Carbon::now(),
            ]
        );

        $this->command->info('User Pribadi (Pro) berhasil ditambahkan!');
    }
}