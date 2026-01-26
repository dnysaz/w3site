<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteStat extends Model
{
    // Daftarkan kolom yang boleh diisi secara massal
    protected $fillable = [
        'site_id',
        'date',
        'clicks'
    ];

    /**
     * Relasi ke model Site
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}