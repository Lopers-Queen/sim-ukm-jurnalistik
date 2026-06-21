<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Anggaran UKM per Divisi (SRS 3.4.10)
 */
class AnggaranUkmDivisi extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'anggaran_ukm_divisi';

    protected $fillable = [
        'periode_id',
        'divisi',
        'bulan',
        'tahun',
        'jumlah_anggaran',
        'jumlah_terpakai',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'bulan'            => 'integer',
            'tahun'            => 'integer',
            'jumlah_anggaran'  => 'decimal:2',
            'jumlah_terpakai'  => 'decimal:2',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['divisi', 'jumlah_anggaran', 'jumlah_terpakai'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ── Relationships ────────────────────────

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeKepengurusan::class, 'periode_id');
    }

    // ── Accessors ────────────────────────────

    /**
     * Sisa anggaran yang belum terpakai.
     */
    public function getSisaAnggaranAttribute(): float
    {
        return (float) $this->jumlah_anggaran - (float) $this->jumlah_terpakai;
    }

    /**
     * Persentase penggunaan anggaran.
     */
    public function getPersentaseTerpakaiAttribute(): float
    {
        if ((float) $this->jumlah_anggaran <= 0) {
            return 0;
        }

        return round(((float) $this->jumlah_terpakai / (float) $this->jumlah_anggaran) * 100, 2);
    }

    /**
     * Nama bulan dalam Bahasa Indonesia.
     */
    public function getNamaBulanAttribute(): string
    {
        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $bulanNames[$this->bulan] ?? '-';
    }
}
