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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Detail Paket & Harga
            $table->string('package_name');
            $table->decimal('amount', 15, 2);
            
            // Status & Info Pembayaran (Dari Midtrans/Gateway)
            $table->string('transaction_status'); // settlement, pending, expire, dll
            $table->string('payment_type')->nullable(); // gopay, bank_transfer, dll
            $table->string('pdf_url')->nullable(); // Simpan link struk dari payment gateway jika ada
            
            // Masa Aktif (Penting!)
            $table->timestamp('expired_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};