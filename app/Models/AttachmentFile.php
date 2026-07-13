<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttachmentFile extends Model
{
    use HasFactory;

    protected $table = 'files';
    protected $primaryKey = 'file_id';
    public $timestamps = false;

    protected $fillable = [
        'file_title',
        'file_name',
        'file_url',
        'file_dir',
        'mime_type',
        'file_desc',
        'file_key',
        'uploader_id',
        'input_date',
        'last_update',
    ];

    protected $casts = [
        'input_date' => 'datetime',
        'last_update' => 'datetime',
    ];

    /**
     * Get the bibliographies that belong to this file attachment.
     */
    public function bibliographies()
    {
        return $this->belongsToMany(
            Biblio::class,
            'biblio_attachment',
            'file_id',
            'biblio_id'
        )->withPivot(['placement', 'access_type', 'access_limit']);
    }
}
