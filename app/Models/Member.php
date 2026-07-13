<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'member';
    protected $primaryKey = 'member_id';
    
    // member_id is a non-incrementing string key
    public $incrementing = false;
    protected $keyType = 'string';
    
    public $timestamps = false;

    protected $fillable = [
        'member_id',
        'member_name',
        'gender',
        'birth_date',
        'member_type_id',
        'member_address',
        'member_mail_address',
        'member_email',
        'postal_code',
        'inst_name',
        'is_new',
        'member_image',
        'pin',
        'member_phone',
        'member_fax',
        'member_since_date',
        'register_date',
        'expire_date',
        'member_notes',
        'is_pending',
        'mpasswd',
        'last_login',
        'last_login_ip',
        'input_date',
        'last_update',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'member_since_date' => 'date',
        'register_date' => 'date',
        'expire_date' => 'date',
        'last_login' => 'datetime',
        'is_pending' => 'integer',
        'gender' => 'integer',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class, 'member_id', 'member_id');
    }

    public function fines()
    {
        return $this->hasMany(Fine::class, 'member_id', 'member_id');
    }

    public function memberType()
    {
        return $this->belongsTo(MemberType::class, 'member_type_id', 'member_type_id');
    }
}
