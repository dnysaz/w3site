<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'prompt',
        'file_name',
        'path'
    ];
}