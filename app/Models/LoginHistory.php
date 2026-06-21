<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Login History (SRS 3.4.2)
 * Catatan setiap percobaan login — TIDAK menggunakan LogsActivity
 * karena tabel ini sendiri sudah merupakan log.
 */
class LoginHistory extends Model
{
    public $timestamps = false;

    protected $table = 'login_history';

    protected $fillable = [
        'anggota_id',
        'ip_address',
        'user_agent',
        'status',
        'keterangan',
        'attempted_at',
    ];

    protected function casts(): array
    {
        return [
            'attempted_at' => 'datetime',
        ];
    }

    // ── Relationships ────────────────────────

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }
}
