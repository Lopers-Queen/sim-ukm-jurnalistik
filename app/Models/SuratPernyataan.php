<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Surat Pernyataan (SRS 3.4.16)
 * Workflow: Pending TTD → Menunggu Konfirmasi → Disetujui/Ditolak
 */
class SuratPernyataan extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'surat_pernyataan';

    protected $fillable = [
        'anggota_id',
        'event_id',
        'anggota_panitia_id',
        'nomor_surat',
        'status',
        'file_pdf',
        'file_ttd',
        'alasan_penolakan',
        'approver_id',
        'tanggal_approval',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_approval' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'approver_id', 'tanggal_approval'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ── Relationships ────────────────────────

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function anggotaPanitia(): BelongsTo
    {
        return $this->belongsTo(AnggotaPanitia::class, 'anggota_panitia_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'approver_id');
    }

    // ── Accessors ────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_ttd'          => 'Menunggu Tanda Tangan',
            'menunggu_konfirmasi'  => 'Menunggu Konfirmasi',
            'disetujui'            => 'Disetujui',
            'ditolak'              => 'Ditolak',
            default                => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending_ttd'         => 'warning',
            'menunggu_konfirmasi' => 'info',
            'disetujui'           => 'success',
            'ditolak'             => 'danger',
            default               => 'secondary',
        };
    }

    // ── Scopes ───────────────────────────────

    public function scopePendingApproval($query)
    {
        return $query->where('status', 'menunggu_konfirmasi');
    }
}
