<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanRule extends Model
{
    protected $table = 'mst_loan_rules';
    protected $primaryKey = 'loan_rules_id';
    public $timestamps = false;

    protected $fillable = [
        'member_type_id',
        'coll_type_id',
        'gmd_id',
        'loan_limit',
        'loan_periode',
        'reborrow_limit',
        'fine_each_day',
        'grace_periode',
        'input_date',
        'last_update',
    ];

    protected $casts = [
        'member_type_id' => 'integer',
        'coll_type_id' => 'integer',
        'gmd_id' => 'integer',
        'loan_limit' => 'integer',
        'loan_periode' => 'integer',
        'reborrow_limit' => 'integer',
        'fine_each_day' => 'integer',
        'grace_periode' => 'integer',
        'input_date' => 'date',
        'last_update' => 'date',
    ];
}
