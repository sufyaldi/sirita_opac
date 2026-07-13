<?php

namespace App\Plugins\VisitorPlugin;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $table = 'member_visitors';
    protected $primaryKey = 'id';

    protected $fillable = [
        'member_id',
        'name',
        'institution',
        'visitor_type'
    ];
}
