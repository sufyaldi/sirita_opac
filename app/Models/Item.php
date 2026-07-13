<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'item';
    protected $primaryKey = 'item_id';
    public $timestamps = false;

    protected $fillable = [
        'biblio_id',
        'call_number',
        'coll_type_id',
        'item_code',
        'inventory_code',
        'received_date',
        'supplier_id',
        'order_no',
        'location_id',
        'order_date',
        'item_status_id',
        'site',
        'source',
        'invoice',
        'price',
        'price_currency',
        'invoice_date',
        'input_date',
        'last_update',
        'uid',
    ];

    protected $casts = [
        'received_date' => 'date',
        'order_date' => 'date',
        'invoice_date' => 'date',
        'input_date' => 'datetime',
        'last_update' => 'datetime',
        'biblio_id' => 'integer',
        'coll_type_id' => 'integer',
        'source' => 'integer',
        'price' => 'integer',
        'uid' => 'integer',
    ];

    public function biblio()
    {
        return $this->belongsTo(Biblio::class, 'biblio_id', 'biblio_id');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'item_code', 'item_code');
    }
}
