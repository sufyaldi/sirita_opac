<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gmd extends Model
{
    protected $table = 'mst_gmd';
    protected $primaryKey = 'gmd_id';
    public $timestamps = false;

    protected $fillable = [
        'gmd_code',
        'gmd_name',
        'icon_image',
        'input_date',
        'last_update',
    ];

    protected $casts = [
        'input_date' => 'date',
        'last_update' => 'date',
    ];
}
