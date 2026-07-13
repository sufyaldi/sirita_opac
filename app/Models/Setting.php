<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'setting_key';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_description'
    ];

    /**
     * Helper statis untuk mengambil konfigurasi secara instan.
     */
    public static function get($key, $default = null)
    {
        $setting = self::find($key);
        return $setting ? $setting->setting_value : $default;
    }

    /**
     * Helper statis untuk menyimpan/memperbarui konfigurasi.
     */
    public static function set($key, $value, $description = null)
    {
        return self::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $value,
                'setting_description' => $description
            ]
        );
    }
}
