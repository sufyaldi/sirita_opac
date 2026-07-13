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
}
