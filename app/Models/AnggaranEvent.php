<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Anggaran Event (SRS 3.4.17)
 */
class AnggaranEvent extends Model
{
    use LogsActivity;

    protected $table = 'anggaran_event';

    protected $fillable = [
        'event_id',
        'item',
        'kategori',
        'qty',
        'harga_satuan',
        'jumlah_dianggarkan',
        'jumlah_realisasi',
        'bukti_transaksi',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'qty'                 => 'integer',
            'harga_satuan'        => 'decimal:2',
            'jumlah_dianggarkan'  => 'decimal:2',
            'jumlah_realisasi'    => 'decimal:2',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['item', 'jumlah_dianggarkan', 'jumlah_realisasi'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ── Relationships ────────────────────────

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // ── Accessors ────────────────────────────

    public function getSelisihAttribute(): float
    {
        return (float) $this->jumlah_dianggarkan - (float) $this->jumlah_realisasi;
    }
}
