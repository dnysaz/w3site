<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek apakah social_id belum ada, baru tambahkan
            if (!Schema::hasColumn('users', 'social_id')) {
                $table->string('social_id')->nullable()->after('email');
            }
            
            // Cek apakah social_type belum ada, baru tambahkan
            if (!Schema::hasColumn('users', 'social_type')) {
                $table->string('social_type')->nullable()->after('social_id');
            }
            
            // Kolom avatar tidak perlu ditambah karena error sebelumnya bilang sudah ada
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['social_id', 'social_type']);
        });
    }
};