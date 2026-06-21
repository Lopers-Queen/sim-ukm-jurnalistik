<?php

namespace App\Enums;

/**
 * Centralized constants and mappings for jabatan (positions),
 * divisi (divisions), and role assignments.
 */
class Jabatan
{
    // ── Jabatan Keys ────────────────────────────

    public const ADMIN = 'admin';
    public const KETUA_UMUM = 'ketua_umum';
    public const WAKIL_KETUA_UMUM = 'wakil_ketua_umum';
    public const SEKRETARIS_UMUM_1 = 'sekretaris_umum_1';
    public const SEKRETARIS_UMUM_2 = 'sekretaris_umum_2';
    public const BENDAHARA_UMUM_1 = 'bendahara_umum_1';
    public const BENDAHARA_UMUM_2 = 'bendahara_umum_2';
    public const KADIV_FOTOGRAFI = 'kadiv_fotografi';
    public const KADIV_PERS_PENYIARAN = 'kadiv_pers_penyiaran';
    public const KADIV_VIDEOGRAFI = 'kadiv_videografi';
    public const KANIT_KOMINFO = 'kanit_kominfo';
    public const KANIT_REDAKSI = 'kanit_redaksi';
    public const KANIT_INVENTORY = 'kanit_inventory';
    public const STAF = 'staf';
    public const ANGGOTA = 'anggota';

    /**
     * All BPI (Badan Pengurus Inti) jabatan keys.
     */
    public const BPI_KEYS = [
        self::KETUA_UMUM,
        self::WAKIL_KETUA_UMUM,
        self::SEKRETARIS_UMUM_1,
        self::SEKRETARIS_UMUM_2,
        self::BENDAHARA_UMUM_1,
        self::BENDAHARA_UMUM_2,
    ];

    /**
     * All Kadiv jabatan keys.
     */
    public const KADIV_KEYS = [
        self::KADIV_FOTOGRAFI,
        self::KADIV_PERS_PENYIARAN,
        self::KADIV_VIDEOGRAFI,
    ];

    /**
     * All Kanit jabatan keys.
     */
    public const KANIT_KEYS = [
        self::KANIT_KOMINFO,
        self::KANIT_REDAKSI,
        self::KANIT_INVENTORY,
    ];

    /**
     * Hierarchical order for display sorting (BPI first, then Kadiv, Kanit, Staf, Anggota).
     */
    public const HIERARCHY_ORDER = [
        self::KETUA_UMUM,
        self::WAKIL_KETUA_UMUM,
        self::SEKRETARIS_UMUM_1,
        self::SEKRETARIS_UMUM_2,
        self::BENDAHARA_UMUM_1,
        self::BENDAHARA_UMUM_2,
        self::KADIV_PERS_PENYIARAN,
        self::KADIV_FOTOGRAFI,
        self::KADIV_VIDEOGRAFI,
        self::KANIT_INVENTORY,
        self::KANIT_KOMINFO,
        self::KANIT_REDAKSI,
        self::STAF,
        self::ANGGOTA,
        self::ADMIN,
    ];

    // ── Label Maps ──────────────────────────────

    /**
     * Human-readable labels for each jabatan key.
     */
    public const LABEL_MAP = [
        self::ADMIN              => 'Administrator',
        self::KETUA_UMUM         => 'Ketua Umum',
        self::WAKIL_KETUA_UMUM   => 'Wakil Ketua Umum',
        self::SEKRETARIS_UMUM_1  => 'Sekretaris Umum 1',
        self::SEKRETARIS_UMUM_2  => 'Sekretaris Umum 2',
        self::BENDAHARA_UMUM_1   => 'Bendahara Umum 1',
        self::BENDAHARA_UMUM_2   => 'Bendahara Umum 2',
        self::KADIV_FOTOGRAFI    => 'Kepala Divisi Fotografi',
        self::KADIV_PERS_PENYIARAN => 'Kepala Divisi Pers & Penyiaran',
        self::KADIV_VIDEOGRAFI   => 'Kepala Divisi Videografi',
        self::KANIT_KOMINFO      => 'Kepala Unit Kominfo',
        self::KANIT_REDAKSI      => 'Kepala Unit Redaksi',
        self::KANIT_INVENTORY    => 'Kepala Unit Inventory',
        self::STAF               => 'Staf',
        self::ANGGOTA            => 'Anggota',
    ];

    /**
     * Human-readable labels for each divisi key.
     */
    public const DIVISI_LABEL_MAP = [
        'fotografi'      => 'Fotografi',
        'pers_penyiaran' => 'Pers & Penyiaran',
        'videografi'     => 'Videografi',
        'kominfo'        => 'Kominfo',
        'redaksi'        => 'Redaksi',
        'inventory'      => 'Inventory',
    ];

    /**
     * Chart display labels for divisi (including BPI where applicable).
     */
    public const DIVISI_CHART_LABELS = [
        'Fotografi', 'Pers & Penyiaran', 'Videografi',
        'Kominfo', 'Redaksi', 'Inventory',
    ];

    /**
     * Chart display keys matching DIVISI_CHART_LABELS.
     */
    public const DIVISI_CHART_KEYS = [
        'fotografi', 'pers_penyiaran', 'videografi',
        'kominfo', 'redaksi', 'inventory',
    ];

    // ── Role Mapping ────────────────────────────

    /**
     * Map jabatan key -> Spatie role name.
     */
    public const ROLE_MAP = [
        self::KETUA_UMUM          => 'ketua_umum',
        self::WAKIL_KETUA_UMUM    => 'wakil_ketua_umum',
        self::SEKRETARIS_UMUM_1   => 'sekretaris_umum_1',
        self::SEKRETARIS_UMUM_2   => 'sekretaris_umum_2',
        self::BENDAHARA_UMUM_1    => 'bendahara_umum_1',
        self::BENDAHARA_UMUM_2    => 'bendahara_umum_2',
        self::KADIV_FOTOGRAFI     => 'kadiv_fotografi',
        self::KADIV_PERS_PENYIARAN => 'kadiv_pers_penyiaran',
        self::KADIV_VIDEOGRAFI    => 'kadiv_videografi',
        self::KANIT_KOMINFO       => 'kanit_kominfo',
        self::KANIT_REDAKSI       => 'kanit_redaksi',
        self::KANIT_INVENTORY     => 'kanit_inventory',
        self::STAF                => 'staf',
        self::ANGGOTA             => 'anggota_aktif',
    ];

    // ── Helper Methods ──────────────────────────

    /**
     * Get human-readable label for a jabatan key.
     */
    public static function label(string $key): string
    {
        return self::LABEL_MAP[$key] ?? $key;
    }

    /**
     * Get human-readable label for a divisi key.
     */
    public static function divisiLabel(string $key): string
    {
        return self::DIVISI_LABEL_MAP[$key] ?? $key;
    }

    /**
     * Get the Spatie role name for a jabatan key.
     */
    public static function roleName(string $key): string
    {
        return self::ROLE_MAP[$key] ?? 'anggota_aktif';
    }

    /**
     * Check if a jabatan key is BPI.
     */
    public static function isBpi(string $key): bool
    {
        return in_array($key, self::BPI_KEYS, true);
    }

    /**
     * Check if a jabatan key is Kadiv.
     */
    public static function isKadiv(string $key): bool
    {
        return in_array($key, self::KADIV_KEYS, true);
    }

    /**
     * Check if a jabatan key is Kanit.
     */
    public static function isKanit(string $key): bool
    {
        return in_array($key, self::KANIT_KEYS, true);
    }
}
