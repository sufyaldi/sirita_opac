<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    protected $table = 'plugins';

    protected $fillable = [
        'key',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
