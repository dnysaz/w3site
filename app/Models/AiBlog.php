<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiBlog extends Model
{
    protected $fillable = [
        'user_id', 
        'title', 
        'image_url', 
        'description', 
        'content', 
        'seo_score', 
        'hashtags'
    ];

    protected $casts = [
        'hashtags' => 'array',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}