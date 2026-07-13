<?php

/**
 * SIRITA SLiMS - Sistem Informasi Riset & Tata Arsip
 * Laravel Edition — Port of SLiMS 9 Bulian
 *
 * @author    Sufyaldy <sufyaldys@gmail.com>
 * @license   AGPL-3.0-or-later
 * @copyright 2026 Sufyaldy
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTakeLog extends Model
{
    protected $table = 'stock_take_log';
    protected $primaryKey = 'log_id';

    protected $fillable = [
        'session_id',
        'item_code',
    ];

    public function session()
    {
        return $this->belongsTo(StockTakeSession::class, 'session_id', 'session_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'item_code');
    }
}
