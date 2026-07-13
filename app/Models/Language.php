<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $table = 'mst_language';
    protected $primaryKey = 'language_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'language_id',
        'language_name',
        'input_date',
        'last_update',
    ];

    protected $casts = [
        'input_date' => 'date',
        'last_update' => 'date',
    ];
}
