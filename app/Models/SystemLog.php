<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $table = 'system_log';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = [
        'log_type',
        'id',
        'log_location',
        'sub_module',
        'action',
        'log_msg',
        'log_date',
    ];

    protected $casts = [
        'log_date' => 'datetime',
    ];
}
