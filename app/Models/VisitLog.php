<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitLog extends Model
{
    protected $fillable = [
        'date',
        'ip_address',
        'browser',
        'platform',
        'country',
        'city',
    ];
}
