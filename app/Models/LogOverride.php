<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Log Override (SRS 3.4.6)
 * Mencatat override eligibility jabatan.
 */
class LogOverride extends Model
{
    public $timestamps = false;

    protected $table = 'log_override';

    protected $fillable = [
        'anggota_id',
        'pelaku_id',
        'jabatan_target',
        'alasan',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    // ── Relationships ────────────────────────

    /**
     * Anggota yang jabatannya di-override.
     */
    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    /**
     * Pejabat yang melakukan override.
     */
    public function pelaku(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'pelaku_id');
    }
}
