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
use Illuminate\Database\Eloquent\Collection;

class OrgUnit extends Model
{
    protected $table = 'org_units';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'short_name',
        'type',
        'parent_id',
        'is_active',
        'sort_order',
        'student_count',
        'address',
        'phone',
        'email',
        'logo',
        'settings',
        'input_date',
        'last_update',
    ];

    protected $casts = [
        'is_active'     => 'integer',
        'sort_order'    => 'integer',
        'student_count' => 'integer',
        'settings'      => 'array',
        'input_date'    => 'datetime',
        'last_update'   => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relasi Hierarki
    // -------------------------------------------------------------------------

    public function parent()
    {
        return $this->belongsTo(OrgUnit::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(OrgUnit::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Ambil semua keturunan (recursive) dalam satu Collection.
     */
    public function descendants(): Collection
    {
        $result = new Collection();
        foreach ($this->children as $child) {
            $result->push($child);
            $result = $result->merge($child->descendants());
        }
        return $result;
    }

    /**
     * Ambil semua ID unit ini + seluruh keturunannya.
     * Digunakan untuk query akreditasi agregat Fakultas (incl. semua Prodi).
     */
    public function selfAndDescendantIds(): array
    {
        return $this->descendants()
                    ->push($this)
                    ->pluck('id')
                    ->toArray();
    }

    // -------------------------------------------------------------------------
    // Relasi Koleksi
    // -------------------------------------------------------------------------

    /** Judul buku yang diklaim oleh unit ini (untuk akreditasi) */
    public function biblios()
    {
        return $this->belongsToMany(
            Biblio::class,
            'biblio_org_unit',
            'unit_id',
            'biblio_id'
        )->withPivot('is_core', 'notes', 'tagged_by', 'tagged_at');
    }

    /** Eksemplar fisik yang dimiliki unit ini */
    public function items()
    {
        return $this->hasMany(Item::class, 'branch_id');
    }

    /** Pustakawan/admin yang terdaftar di unit ini */
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_org_units',
            'unit_id',
            'user_id'
        )->withPivot('role', 'is_default');
    }

    // -------------------------------------------------------------------------
    // Settings Helpers
    // -------------------------------------------------------------------------

    public function getSetting(string $key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    public function loanDays(): int   { return (int) $this->getSetting('loan_days', 14); }
    public function finePerDay(): int { return (int) $this->getSetting('fine_per_day', 500); }
    public function maxLoans(): int   { return (int) $this->getSetting('max_loans', 3); }

    // -------------------------------------------------------------------------
    // Helpers Akreditasi
    // -------------------------------------------------------------------------

    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    public function totalTitles(): int
    {
        $ids = $this->selfAndDescendantIds();
        return \DB::table('biblio_org_unit')
                  ->whereIn('unit_id', $ids)
                  ->distinct()
                  ->count('biblio_id');
    }

    public function totalItems(): int
    {
        $ids = $this->selfAndDescendantIds();
        return Item::whereIn('branch_id', $ids)->count();
    }

    public function collectionRatio(): string
    {
        if (!$this->student_count || $this->student_count === 0) {
            return 'N/A';
        }
        $titles = $this->totalTitles();
        $ratio = round($titles / $this->student_count, 1);
        return "1:{$ratio}";
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    // -------------------------------------------------------------------------
    // Type Labels
    // -------------------------------------------------------------------------

    public static function typeLabels(): array
    {
        return [
            'university'   => 'Universitas',
            'main_library' => 'UPT Perpustakaan Pusat',
            'faculty'      => 'Perpustakaan Fakultas',
            'department'   => 'Perpustakaan Program Studi',
            'lp'           => 'Lembaga Penelitian',
            'upt'          => 'UPT Lainnya',
            'pusat'        => 'Pusat Studi',
            'other'        => 'Lainnya',
        ];
    }

    public function typeLabel(): string
    {
        return static::typeLabels()[$this->type] ?? $this->type;
    }
}
