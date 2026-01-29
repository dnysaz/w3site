<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'package_name',
        'amount',
        'transaction_status',
        'payment_type',
        'snap_token',
        'expired_at', // Pastikan kolom ini masuk fillable
    ];

    /**
     * Casting kolom agar otomatis menjadi objek Carbon (Tanggal).
     * Sangat penting supaya fungsi ->format() dan ->diffInDays() bekerja.
     */
    protected $casts = [
        'expired_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Helper untuk mengecek apakah transaksi ini masih aktif.
     * Bisa dipakai di blade: @if($trx->isActive()) ... @endif
     */
    public function isActive()
    {
        return $this->expired_at && $this->expired_at->isFuture();
    }
}