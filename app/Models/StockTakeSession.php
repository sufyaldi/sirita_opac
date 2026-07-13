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

class StockTakeSession extends Model
{
    protected $table = 'stock_take_session';
    protected $primaryKey = 'session_id';

    protected $fillable = [
        'session_name',
        'status',
    ];

    public function logs()
    {
        return $this->hasMany(StockTakeLog::class, 'session_id', 'session_id');
    }
}
