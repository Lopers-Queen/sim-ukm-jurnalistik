<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Model Event (SRS 3.4.12)
 */
class Event extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'event';

    protected $fillable = [
        'nama_event',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'status',
        'pic_id',
        'anggaran_total',
        'periode_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai'   => 'date',
            'tanggal_selesai' => 'date',
            'anggaran_total'  => 'decimal:2',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_event', 'status', 'anggaran_total'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ── Relationships ────────────────────────

    public function pic(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'pic_id');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeKepengurusan::class, 'periode_id');
    }

    public function divisiPanitia(): HasMany
    {
        return $this->hasMany(DivisiPanitia::class, 'event_id');
    }

    public function anggotaPanitia(): HasMany
    {
        return $this->hasMany(AnggotaPanitia::class, 'event_id');
    }

    public function anggaranEvent(): HasMany
    {
        return $this->hasMany(AnggaranEvent::class, 'event_id');
    }

    public function suratPernyataan(): HasMany
    {
        return $this->hasMany(SuratPernyataan::class, 'event_id');
    }

    public function laporanPascaEvent(): HasOne
    {
        return $this->hasOne(LaporanPascaEvent::class, 'event_id');
    }

    // ── Accessors ────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'        => 'Draft',
            'direncanakan' => 'Direncanakan',
            'aktif'        => 'Sedang Berlangsung',
            'selesai'      => 'Selesai',
            'batal'        => 'Dibatalkan',
            default        => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft'        => 'secondary',
            'direncanakan' => 'info',
            'aktif'        => 'primary',
            'selesai'      => 'success',
            'batal'        => 'danger',
            default        => 'secondary',
        };
    }

    /**
     * Total realisasi anggaran event.
     */
    public function getTotalRealisasiAttribute(): float
    {
        return (float) $this->anggaranEvent()->sum('jumlah_realisasi');
    }

    /**
     * Total panitia yang terdaftar.
     */
    public function getJumlahPanitiaAttribute(): int
    {
        return $this->anggotaPanitia()->count();
    }

    // ── Scopes ───────────────────────────────

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeMendatang($query)
    {
        return $query->where('tanggal_mulai', '>=', now())->where('status', '!=', 'batal');
    }
}
