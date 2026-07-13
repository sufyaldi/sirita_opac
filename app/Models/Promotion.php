<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'promotions';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'content',
        'image_url',
        'is_active',
        'sort_order',
        'publish_date',
        'input_date',
        'last_update'
    ];

    protected $casts = [
        'is_active' => 'integer',
        'sort_order' => 'integer',
        'publish_date' => 'datetime',
        'input_date' => 'datetime',
        'last_update' => 'datetime'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1)->orderBy('sort_order')->orderByDesc('publish_date');
    }
}
