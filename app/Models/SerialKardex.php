<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SerialKardex extends Model
{
    protected $table = 'serial_kardex';
    protected $primaryKey = 'kardex_id';

    protected $fillable = [
        'subscription_id',
        'issue_no',
        'publish_date',
        'receive_date',
        'status',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'receive_date' => 'date',
    ];

    public function subscription()
    {
        return $this->belongsTo(SerialSubscription::class, 'subscription_id', 'subscription_id');
    }
}
