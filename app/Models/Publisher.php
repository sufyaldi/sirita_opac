<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $table = 'mst_publisher';
    protected $primaryKey = 'publisher_id';
    public $timestamps = false;

    protected $fillable = [
        'publisher_name',
        'input_date',
        'last_update',
    ];

    protected $casts = [
        'input_date' => 'date',
        'last_update' => 'date',
    ];
}
