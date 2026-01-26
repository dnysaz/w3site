<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            // Menghubungkan situs ke user (Kunci Utama)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('subdomain')->unique();

            $table->string('repository_url')->nullable();

            $table->unsignedBigInteger('clicks_count')->default(0);
            
            // UPDATE: Diubah menjadi nullable() agar bisa simpan nama dulu tanpa file
            $table->string('folder_path')->nullable(); 
            
            // UPDATE: Default status adalah 'pending' karena file belum diupload
            $table->string('status')->default('pending'); 
            
            $table->timestamps();
        });
        
        // Tambahkan kolom membership ke tabel users
        Schema::table('users', function (Blueprint $table) {
            // Menggunakan 'package' (integer) sesuai dengan logika di Blade Anda sebelumnya
            // 0: Gratis, 1: Pemula, 2: Pro
            if (!Schema::hasColumn('users', 'package')) {
                $table->integer('package')->default(0)->after('email');
            }
            
            if (!Schema::hasColumn('users', 'package_expired_at')) {
                $table->timestamp('package_expired_at')->nullable()->after('package');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['package', 'package_expired_at']);
        });
    }
};