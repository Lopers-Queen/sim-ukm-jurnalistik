<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Periode Kepengurusan (SRS 3.4.5)
 */
class PeriodeKepengurusan extends Model
{
    use LogsActivity;

    protected $table = 'periode_kepengurusan';

    protected $fillable = [
        'nama_periode',
        'tahun_mulai',
        'tahun_selesai',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
        'status',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai'   => 'date',
            'tanggal_selesai' => 'date',
            'is_active'       => 'boolean',
            'tahun_mulai'     => 'integer',
            'tahun_selesai'   => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_periode', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ── Relationships ────────────────────────

    public function riwayatKepengurusan(): HasMany
    {
        return $this->hasMany(RiwayatKepengurusan::class, 'periode_id');
    }

    public function rekrutmen(): HasMany
    {
        return $this->hasMany(Rekrutmen::class, 'periode_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'periode_id');
    }

    public function anggaranDivisi(): HasMany
    {
        return $this->hasMany(AnggaranUkmDivisi::class, 'periode_id');
    }

    // ── Helpers ──────────────────────────────

    /**
     * Scope: hanya periode yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Accessor: label periode (contoh: "2024/2025").
     */
    public function getLabelAttribute(): string
    {
        return $this->nama_periode;
    }
}
