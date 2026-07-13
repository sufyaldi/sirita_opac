<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;

    protected $table = 'user_group';
    protected $primaryKey = 'group_id';
    public $timestamps = false;

    protected $fillable = [
        'group_name',
        'input_date',
        'last_update',
    ];

    protected $casts = [
        'input_date' => 'date',
        'last_update' => 'date',
    ];
}
