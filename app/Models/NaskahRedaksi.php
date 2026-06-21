<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Naskah Redaksi (SRS 3.4.11)
 */
class NaskahRedaksi extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'naskah_redaksi';

    protected $fillable = [
        'judul',
        'konten',
        'penulis_id',
        'editor_id',
        'status',
        'kategori',
        'tanggal_publish',
        'catatan_editor',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_publish' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['judul', 'status', 'kategori'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ── Relationships ────────────────────────

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'penulis_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'editor_id');
    }

    // ── Accessors ────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'     => 'Draft',
            'review'    => 'Dalam Review',
            'revisi'    => 'Perlu Revisi',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            'published' => 'Dipublikasikan',
            default     => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft'     => 'secondary',
            'review'    => 'info',
            'revisi'    => 'warning',
            'disetujui' => 'success',
            'ditolak'   => 'danger',
            'published' => 'primary',
            default     => 'secondary',
        };
    }
}
