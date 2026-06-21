<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Jadwal Piket (FR-06)
 * Jadwal piket diacak dari seluruh anggota aktif UKM Jurnalistik.
 * Lokasi tetap: Sekretariat UKM Jurnalistik.
 */
class JadwalShift extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'jadwal_shift';

    protected $fillable = [
        'anggota_id',
        'hari',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['hari', 'anggota_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function getHariLabelAttribute(): string
    {
        return ucfirst($this->hari);
    }
}
