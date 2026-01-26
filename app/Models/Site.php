<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Site extends Model
{
    // Mengizinkan mass assignment agar bisa disimpan lewat Controller
    protected $fillable = ['user_id', 'subdomain', 'folder_path', 'status','repository_url','clicks_count'];

    /**
     * Relasi: Satu situs dimiliki oleh satu User
     */
    public function user(): BelongsTo
    {
        // Gunakan ::class (titik dua dua kali), bukan titik (.)
        return $this->belongsTo(User::class);
    }
}