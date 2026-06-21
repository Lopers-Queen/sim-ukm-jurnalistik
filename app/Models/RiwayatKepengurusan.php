<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Riwayat Kepengurusan (SRS 3.4.4)
 */
class RiwayatKepengurusan extends Model
{
    use LogsActivity;

    protected $table = 'riwayat_kepengurusan';

    protected $fillable = [
        'anggota_id',
        'periode_id',
        'jabatan',
        'divisi',
        'tanggal_mulai',
        'tanggal_selesai',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai'   => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['jabatan', 'divisi', 'tanggal_mulai', 'tanggal_selesai'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ── Relationships ────────────────────────

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeKepengurusan::class, 'periode_id');
    }

    // ── Accessors ────────────────────────────

    public function getJabatanLabelAttribute(): string
    {
        $map = [
            'ketua_umum'           => 'Ketua Umum',
            'wakil_ketua_umum'     => 'Wakil Ketua Umum',
            'sekretaris_umum_1'    => 'Sekretaris Umum 1',
            'sekretaris_umum_2'    => 'Sekretaris Umum 2',
            'bendahara_umum_1'     => 'Bendahara Umum 1',
            'bendahara_umum_2'     => 'Bendahara Umum 2',
            'kadiv_fotografi'      => 'Kadiv Fotografi',
            'kadiv_pers_penyiaran' => 'Kadiv Pers & Penyiaran',
            'kadiv_videografi'     => 'Kadiv Videografi',
            'kanit_kominfo'        => 'Kanit Kominfo',
            'kanit_redaksi'        => 'Kanit Redaksi',
            'kanit_inventory'      => 'Kanit Inventory',
            'staf'                 => 'Staf',
            'anggota'              => 'Anggota',
        ];

        return $map[$this->jabatan] ?? $this->jabatan;
    }
}
