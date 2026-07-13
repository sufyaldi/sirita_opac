<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $table = 'holiday';
    protected $primaryKey = 'holiday_id';
    public $timestamps = false;

    protected $fillable = [
        'holiday_dayname',
        'holiday_date',
        'description',
    ];

    protected $casts = [
        'holiday_date' => 'date',
    ];
}
