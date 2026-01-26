<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Linktree extends Model
{
    protected $fillable = [
        'user_id',
        'slug',             // Alamat URL unik
        'title',            // Nama profil
        'image_url',
        'links_json',       // Data link mentah
        'design_concept',
        'html_content',     // Output AI
        'css_content',
        'is_active',        // Status aktif/draft
        'views_count',      // Counter pengunjung
        'last_accessed_at'  // Terakhir diakses
    ];

    /**
     * Konversi data otomatis
     */
    protected $casts = [
        'links_json' => 'array',
        'is_active' => 'boolean',
        'last_accessed_at' => 'datetime',
        'views_count' => 'integer',
    ];

    /**
     * Boot function untuk logika otomatis
     */
    protected static function boot()
    {
        parent::boot();

        // Otomatis buat slug jika belum ada saat membuat data baru
        static::creating(function ($linktree) {
            if (empty($linktree->slug)) {
                $linktree->slug = static::generateUniqueSlug($linktree->title);
            }
        });
    }

    /**
     * Fungsi helper untuk generate slug unik
     */
    private static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();
        
        return $count > 0 ? "{$slug}-" . ($count + 1) : $slug;
    }

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}