<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Laporan Pasca Event (SRS 3.4.18)
 */
class LaporanPascaEvent extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'laporan_pasca_event';

    protected $fillable = [
        'event_id',
        'pelapor_id',
        'ringkasan',
        'evaluasi',
        'saran',
        'jumlah_peserta',
        'file_laporan',
        'file_dokumentasi',
        'tanggal_submit',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_peserta' => 'integer',
            'tanggal_submit' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['ringkasan', 'tanggal_submit'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ── Relationships ────────────────────────

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'pelapor_id');
    }
}
