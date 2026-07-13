<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = 'loan';
    protected $primaryKey = 'loan_id';
    public $timestamps = false;

    protected $fillable = [
        'item_code',
        'member_id',
        'loan_date',
        'due_date',
        'renewed',
        'loan_rules_id',
        'actual',
        'is_lent',
        'is_return',
        'return_date',
        'input_date',
        'last_update',
        'uid',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'actual' => 'date',
        'return_date' => 'date',
        'renewed' => 'integer',
        'loan_rules_id' => 'integer',
        'is_lent' => 'integer',
        'is_return' => 'integer',
        'input_date' => 'datetime',
        'last_update' => 'datetime',
        'uid' => 'integer',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    public function rule()
    {
        return $this->belongsTo(LoanRule::class, 'loan_rules_id', 'loan_rules_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'item_code');
    }
}
