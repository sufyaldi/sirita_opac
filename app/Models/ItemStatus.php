<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStatus extends Model
{
    use HasFactory;

    protected $table = 'mst_item_status';
    protected $primaryKey = 'item_status_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'item_status_id',
        'item_status_name',
        'rules',
        'no_loan',
        'skip_stock_take',
        'input_date',
        'last_update',
    ];

    protected $casts = [
        'input_date' => 'date',
        'last_update' => 'date',
    ];
}
