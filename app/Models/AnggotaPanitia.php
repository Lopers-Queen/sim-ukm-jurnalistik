<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Anggota Panitia (SRS 3.4.14)
 * CONSTRAINT: UNIQUE(event_id, anggota_id) — 1 anggota = 1 posisi per event
 */
class AnggotaPanitia extends Model
{
    use LogsActivity;

    protected $table = 'anggota_panitia';

    protected $fillable = [
        'event_id',
        'anggota_id',
        'divisi_panitia_id',
        'jabatan_panitia',
        'catatan',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['jabatan_panitia', 'divisi_panitia_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ── Relationships ────────────────────────

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function divisiPanitia(): BelongsTo
    {
        return $this->belongsTo(DivisiPanitia::class, 'divisi_panitia_id');
    }

    public function suratPernyataan(): HasOne
    {
        return $this->hasOne(SuratPernyataan::class, 'anggota_panitia_id');
    }

    // ── Helpers ──────────────────────────────

    /**
     * Cek apakah anggota ini adalah anggota pasif yang butuh surat pernyataan.
     */
    public function butuhSuratPernyataan(): bool
    {
        return $this->anggota && $this->anggota->status_keanggotaan === 'pasif';
    }
}
