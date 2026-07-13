<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $table = 'fines';
    protected $primaryKey = 'fines_id';
    public $timestamps = false;

    protected $fillable = [
        'fines_date',
        'member_id',
        'debet',
        'credit',
        'description',
    ];

    protected $casts = [
        'fines_date' => 'date',
        'debet' => 'integer',
        'credit' => 'integer',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }
}
