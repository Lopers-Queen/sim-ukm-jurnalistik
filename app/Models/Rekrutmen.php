<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Rekrutmen (SRS 3.4.8)
 */
class Rekrutmen extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'rekrutmen';

    protected $fillable = [
        'periode_id',
        'nama_rekrutmen',
        'tanggal_buka',
        'tanggal_tutup',
        'status',
        'deskripsi',
        'kuota',
        'persyaratan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_buka'  => 'date',
            'tanggal_tutup' => 'date',
            'kuota'         => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_rekrutmen', 'status', 'kuota'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ── Relationships ────────────────────────

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeKepengurusan::class, 'periode_id');
    }

    // ── Scopes ───────────────────────────────

    public function scopeAktif($query)
    {
        return $query->where('status', 'dibuka');
    }
}
