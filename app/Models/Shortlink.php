<?php

// app/Models/Shortlink.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Shortlink extends Model
{
    protected $fillable = ['user_id', 'destination_url', 'slug', 'clicks', 'is_active'];

    protected static function boot()
    {
        parent::boot();
        // Otomatis generate slug 6 karakter saat membuat record baru
        static::creating(function ($shortlink) {
            $shortlink->slug = Str::random(6);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}