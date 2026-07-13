<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
