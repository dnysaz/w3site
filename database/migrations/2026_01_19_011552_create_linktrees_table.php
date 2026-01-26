<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('linktrees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Kolom Identitas & URL
            $table->string('title'); // Nama profil
            $table->string('slug')->unique(); // Alamat: w3site.id/nama-user
            $table->string('image_url')->nullable();
            
            // Data Input User & AI
            $table->json('links_json'); // Menyimpan array [label, url]
            $table->text('design_concept'); // Deskripsi keinginan user
            $table->longText('html_content')->nullable(); // Output HTML dari AI
            $table->longText('css_content')->nullable();  // Output CSS dari AI
            
            // Monitoring & Statistik (Fitur Baru)
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('views_count')->default(0); 
            $table->timestamp('last_accessed_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('linktrees');
    }
};