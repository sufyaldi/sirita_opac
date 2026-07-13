<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biblio extends Model
{
    protected $table = 'biblio';
    protected $primaryKey = 'biblio_id';
    
    // Disable default timestamps if slims handles them customly
    public $timestamps = false;

    protected $fillable = [
        'gmd_id',
        'title',
        'sor',
        'edition',
        'isbn_issn',
        'publisher_id',
        'publish_year',
        'collation',
        'series_title',
        'call_number',
        'language_id',
        'source',
        'publish_place_id',
        'classification',
        'notes',
        'image',
        'file_att',
        'opac_hide',
        'promoted',
        'labels',
        'frequency_id',
        'spec_detail_info',
        'content_type_id',
        'media_type_id',
        'carrier_type_id',
        'input_date',
        'last_update',
        'uid',
    ];

    protected $casts = [
        'input_date' => 'datetime',
        'last_update' => 'datetime',
        'opac_hide' => 'integer',
        'promoted' => 'integer',
    ];

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id', 'publisher_id');
    }

    public function gmd()
    {
        return $this->belongsTo(Gmd::class, 'gmd_id', 'gmd_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'biblio_id', 'biblio_id');
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'biblio_author', 'biblio_id', 'author_id')
                    ->withPivot('level');
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'biblio_topic', 'biblio_id', 'topic_id')
                    ->withPivot('level');
    }

    public function attachments()
    {
        return $this->belongsToMany(AttachmentFile::class, 'biblio_attachment', 'biblio_id', 'file_id')
                    ->withPivot(['placement', 'access_type', 'access_limit']);
    }

    public function publishPlace()
    {
        return $this->belongsTo(Place::class, 'publish_place_id', 'place_id');
    }

    /**
     * Unit perpustakaan yang mengklaim judul ini (untuk akreditasi BAN-PT/LAM).
     */
    public function orgUnits()
    {
        return $this->belongsToMany(
            OrgUnit::class,
            'biblio_org_unit',
            'biblio_id',
            'unit_id'
        )->withPivot('is_core', 'notes', 'tagged_by', 'tagged_at');
    }
}

