<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $blueprint) {
            // Kita tambahkan nullable agar data lama tidak error (diisi null)
            // Kita letakkan setelah column 'payment_type' agar rapi
            $blueprint->string('snap_token')->nullable()->after('payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $blueprint) {
            $blueprint->dropColumn('snap_token');
        });
    }
};