<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Notulensi (SRS 3.4.7)
 */
class Notulensi extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'notulensi';

    protected $fillable = [
        'judul',
        'tanggal_rapat',
        'lokasi',
        'jenis_rapat',
        'isi_notulensi',
        'pencatat_id',
        'daftar_hadir',
        'file_lampiran',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_rapat' => 'date',
            'daftar_hadir'  => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['judul', 'tanggal_rapat', 'jenis_rapat'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ── Relationships ────────────────────────

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'pencatat_id');
    }

    // ── Accessors ────────────────────────────

    public function getJenisRapatLabelAttribute(): string
    {
        return match ($this->jenis_rapat) {
            'rapat_rutin'    => 'Rapat Rutin',
            'rapat_khusus'   => 'Rapat Khusus',
            'rapat_evaluasi' => 'Rapat Evaluasi',
            'rapat_kerja'    => 'Rapat Kerja',
            'rapat_pleno'    => 'Rapat Pleno',
            default          => $this->jenis_rapat,
        };
    }

    /**
     * Jumlah anggota yang hadir.
     */
    public function getJumlahHadirAttribute(): int
    {
        return is_array($this->daftar_hadir) ? count($this->daftar_hadir) : 0;
    }
}
