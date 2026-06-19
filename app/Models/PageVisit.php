<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'path', 'url', 'referrer', 'ip_address', 'country',
        'country_code', 'city', 'device', 'user_agent', 'created_at',
    ];

    protected $casts = ['created_at' => 'datetime'];
}
