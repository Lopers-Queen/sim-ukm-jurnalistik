<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Divisi Panitia (SRS 3.4.13)
 */
class DivisiPanitia extends Model
{
    protected $table = 'divisi_panitia';

    protected $fillable = [
        'event_id',
        'nama_divisi',
        'deskripsi',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
        ];
    }

    // ── Relationships ────────────────────────

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function anggotaPanitia(): HasMany
    {
        return $this->hasMany(AnggotaPanitia::class, 'divisi_panitia_id');
    }

    // ── Accessors ────────────────────────────

    public function getJumlahAnggotaAttribute(): int
    {
        return $this->anggotaPanitia()->count();
    }
}
