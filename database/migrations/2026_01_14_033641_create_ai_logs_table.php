<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('feature')->nullable(); // Contoh: 'generate_site' atau 'edit_code'
            $table->text('prompt')->nullable();  // Menyimpan apa yang ditanyakan user (opsional)
            $table->timestamps(); // Ini yang digunakan middleware untuk whereMonth()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_logs');
    }
};
