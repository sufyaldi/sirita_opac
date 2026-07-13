<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SerialSubscription extends Model
{
    protected $table = 'serial_subscription';
    protected $primaryKey = 'subscription_id';

    protected $fillable = [
        'title',
        'publisher_name',
        'period_start',
        'period_end',
        'frequency',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function kardexes()
    {
        return $this->hasMany(SerialKardex::class, 'subscription_id', 'subscription_id');
    }
}
