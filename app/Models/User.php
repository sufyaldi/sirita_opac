<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'user_id';
    
    public $timestamps = false;

    protected $fillable = [
        'username',
        'realname',
        'passwd',
        '2fa',
        'email',
        'user_type',
        'user_image',
        'social_media',
        'last_login',
        'last_login_ip',
        'groups',
        'forgot',
        'admin_template',
        'input_date',
        'last_update',
    ];

    protected $hidden = [
        'passwd',
        'forgot',
    ];

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPasswordName()
    {
        return 'passwd';
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->passwd;
    }

    /**
     * Accessor for groups (handles PHP serialized data).
     */
    public function getGroupsAttribute($value)
    {
        if (empty($value)) {
            return [];
        }
        
        try {
            // Attempt to unserialize PHP serialized data
            $unserialized = @unserialize($value);
            return $unserialized !== false ? $unserialized : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Mutator for groups (saves as PHP serialized string).
     */
    public function setGroupsAttribute($value)
    {
        $this->attributes['groups'] = serialize(is_array($value) ? $value : [$value]);
    }

    /**
     * Unit kerja / cabang tempat user terdaftar.
     */
    public function orgUnits()
    {
        return $this->belongsToMany(
            OrgUnit::class,
            'user_org_units',
            'user_id',
            'unit_id'
        )->withPivot('role', 'is_default');
    }

    /**
     * Mengambil active branch ID dari session, atau fallback ke default branch user.
     */
    public function activeBranchId()
    {
        if (session()->has('active_branch_id')) {
            return session('active_branch_id');
        }

        $default = $this->orgUnits()->wherePivot('is_default', 1)->first();
        if ($default) {
            session(['active_branch_id' => $default->id]);
            return $default->id;
        }

        $first = $this->orgUnits()->first();
        if ($first) {
            session(['active_branch_id' => $first->id]);
            return $first->id;
        }

        return null;
    }

    /**
     * Mengambil daftar ID cabang yang boleh diakses user ini.
     */
    public function allowedBranchIds(): array
    {
        return $this->orgUnits()->pluck('org_units.id')->toArray();
    }
}

