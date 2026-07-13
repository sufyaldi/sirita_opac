<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $table = 'mst_topic';
    protected $primaryKey = 'topic_id';
    public $timestamps = false;

    protected $fillable = [
        'topic',
        'topic_type',
        'auth_list',
        'classification',
        'input_date',
        'last_update',
    ];

    protected $casts = [
        'input_date' => 'date',
        'last_update' => 'date',
    ];
}
