<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Template Kepanitiaan (SRS 3.4.15)
 */
class TemplateKepanitiaan extends Model
{
    protected $table = 'template_kepanitiaan';

    protected $fillable = [
        'nama_template',
        'struktur',
        'deskripsi',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'struktur'  => 'array',
            'is_active' => 'boolean',
        ];
    }

    // ── Scopes ───────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
