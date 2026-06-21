<?php

namespace App\Models;

use App\Enums\Jabatan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

/**
 * Model Anggota UKM Jurnalistik
 * Tabel utama untuk data anggota (menggantikan User default Laravel).
 * Login menggunakan NIM, password default DDMMYYYY dari tanggal lahir.
 *
 * @property int $id
 * @property string $nim
 * @property string $nama_lengkap
 * @property string $email
 * @property string $password
 * @property \Carbon\Carbon $tanggal_lahir
 * @property string|null $tempat_lahir
 * @property string $jenis_kelamin
 * @property string|null $no_hp
 * @property string|null $alamat
 * @property string|null $program_studi
 * @property string|null $jurusan
 * @property string|null $foto_profil
 * @property string|null $divisi
 * @property string $jabatan_struktural
 * @property string $status_keanggotaan
 * @property \Carbon\Carbon $tanggal_bergabung
 * @property bool $is_first_login
 * @property bool $is_locked
 * @property \Carbon\Carbon|null $locked_until
 * @property int $failed_login_attempts
 */
class Anggota extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\AnggotaFactory> */
    use HasFactory, HasRoles, LogsActivity, Notifiable, SoftDeletes;

    /**
     * Nama tabel yang digunakan model ini.
     */
    protected $table = 'anggota';

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nim',
        'nama_lengkap',
        'email',
        'password',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'no_hp',
        'alamat',
        'program_studi',
        'jurusan',
        'foto_profil',
        'divisi',
        'jabatan_struktural',
        'status_keanggotaan',
        'tanggal_bergabung',
        'is_first_login',
        'is_locked',
        'locked_until',
        'failed_login_attempts',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir'      => 'date',
            'tanggal_bergabung'  => 'date',
            'is_first_login'     => 'boolean',
            'is_locked'          => 'boolean',
            'locked_until'       => 'datetime',
            'email_verified_at'  => 'datetime',
            'password'           => 'hashed',
            'failed_login_attempts' => 'integer',
        ];
    }

    // ──────────────────────────────────────────
    // Activity Log Configuration
    // ──────────────────────────────────────────

    /**
     * Konfigurasi Spatie Activity Log.
     * Mencatat perubahan pada field penting.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nama_lengkap', 'email', 'divisi',
                'jabatan_struktural', 'status_keanggotaan',
                'is_locked', 'is_first_login',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "Anggota {$this->nama_lengkap} telah di-{$eventName}");
    }

    // ──────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────

    /**
     * Scope: exclude admin accounts from results.
     */
    public function scopeNonAdmin($query)
    {
        return $query->where('jabatan_struktural', '!=', 'admin');
    }

    /**
     * Scope: only active members.
     */
    public function scopeAktif($query)
    {
        return $query->where('status_keanggotaan', 'aktif');
    }

    /**
     * Scope: search by NIM, name, or email.
     */
    public function scopeSearch($query, ?string $search)
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('nim', 'like', "%{$search}%")
              ->orWhere('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    /**
     * Scope: filter by divisi.
     */
    public function scopeDivisi($query, ?string $divisi)
    {
        if (! $divisi) {
            return $query;
        }

        return $query->where('divisi', $divisi);
    }

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    /**
     * Riwayat login anggota.
     */
    public function loginHistories(): HasMany
    {
        return $this->hasMany(LoginHistory::class, 'anggota_id');
    }

    /**
     * Riwayat kepengurusan anggota di berbagai periode.
     */
    public function riwayatKepengurusan(): HasMany
    {
        return $this->hasMany(RiwayatKepengurusan::class, 'anggota_id');
    }

    /**
     * Log override yang dilakukan terhadap anggota ini.
     */
    public function logOverrides(): HasMany
    {
        return $this->hasMany(LogOverride::class, 'anggota_id');
    }

    /**
     * Log override yang dilakukan oleh anggota ini.
     */
    public function logOverridesDilakukan(): HasMany
    {
        return $this->hasMany(LogOverride::class, 'pelaku_id');
    }

    /**
     * Notulensi yang dicatat oleh anggota ini.
     */
    public function notulensi(): HasMany
    {
        return $this->hasMany(Notulensi::class, 'pencatat_id');
    }

    /**
     * Jadwal shift anggota.
     */
    public function jadwalShift(): HasMany
    {
        return $this->hasMany(JadwalShift::class, 'anggota_id');
    }

    /**
     * Naskah yang ditulis oleh anggota ini.
     */
    public function naskahDitulis(): HasMany
    {
        return $this->hasMany(NaskahRedaksi::class, 'penulis_id');
    }

    /**
     * Naskah yang diedit oleh anggota ini.
     */
    public function naskahDiedit(): HasMany
    {
        return $this->hasMany(NaskahRedaksi::class, 'editor_id');
    }

    /**
     * Event yang dijadikan PIC oleh anggota ini.
     */
    public function eventSebagaiPic(): HasMany
    {
        return $this->hasMany(Event::class, 'pic_id');
    }

    /**
     * Penugasan kepanitiaan anggota.
     */
    public function kepanitiaan(): HasMany
    {
        return $this->hasMany(AnggotaPanitia::class, 'anggota_id');
    }

    /**
     * Surat pernyataan yang dimiliki anggota.
     */
    public function suratPernyataan(): HasMany
    {
        return $this->hasMany(SuratPernyataan::class, 'anggota_id');
    }

    /**
     * Surat pernyataan yang disetujui/ditolak oleh anggota ini.
     */
    public function suratPernyataanApproved(): HasMany
    {
        return $this->hasMany(SuratPernyataan::class, 'approver_id');
    }

    /**
     * Laporan pasca event yang ditulis oleh anggota ini.
     */
    public function laporanPascaEvent(): HasMany
    {
        return $this->hasMany(LaporanPascaEvent::class, 'pelapor_id');
    }

    // ──────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────

    /**
     * Accessor: URL foto profil (default avatar jika kosong).
     */
    public function getFotoProfilUrlAttribute(): string
    {
        if ($this->foto_profil && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->foto_profil)) {
            return \Illuminate\Support\Facades\Storage::url($this->foto_profil);
        }

        // Default avatar: inisial
        return '';
    }

    /**
     * Upload foto profil baru, hapus yang lama.
     */
    public function uploadFotoProfil($file): void
    {
        // Hapus foto lama
        $this->deleteFotoProfil();

        // Simpan baru
        $path = $file->store('profile-photos', 'public');
        $this->update(['foto_profil' => $path]);
    }

    /**
     * Hapus foto profil dari storage.
     */
    public function deleteFotoProfil(): void
    {
        if ($this->foto_profil && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->foto_profil)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($this->foto_profil);
            $this->update(['foto_profil' => null]);
        }
    }

    /**
     * Accessor: Nama lengkap (alias untuk kompatibilitas).
     */
    public function getFullNameAttribute(): string
    {
        return $this->nama_lengkap;
    }

    /**
     * Accessor: Nama jabatan yang lebih readable.
     */
    public function getJabatanLengkapAttribute(): string
    {
        return Jabatan::label($this->jabatan_struktural);
    }

    /**
     * Accessor: Label status keanggotaan.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_keanggotaan) {
            'aktif'  => 'Aktif',
            'pasif'  => 'Pasif',
            'alumni' => 'Alumni',
            default  => $this->status_keanggotaan,
        };
    }

    /**
     * Accessor: Nama divisi yang readable.
     */
    public function getDivisiLabelAttribute(): string
    {
        if (! $this->divisi) {
            return '-';
        }

        return Jabatan::divisiLabel($this->divisi);
    }

    // ──────────────────────────────────────────
    // Helper Methods
    // ──────────────────────────────────────────

    /**
     * Cek apakah ini login pertama kali (wajib ganti password).
     */
    public function isFirstLogin(): bool
    {
        return (bool) $this->is_first_login;
    }

    /**
     * Cek apakah akun sedang terkunci.
     */
    public function isLocked(): bool
    {
        if (! $this->is_locked) {
            return false;
        }

        // Cek apakah waktu lock sudah lewat
        if ($this->locked_until && $this->locked_until->isPast()) {
            $this->update([
                'is_locked' => false,
                'locked_until' => null,
                'failed_login_attempts' => 0,
            ]);

            return false;
        }

        return true;
    }

    /**
     * Hitung masa keanggotaan dalam tahun.
     */
    public function masaKeanggotaan(): int
    {
        return $this->tanggal_bergabung
            ? (int) $this->tanggal_bergabung->diffInYears(now())
            : 0;
    }

    /**
     * Increment gagal login dan kunci akun jika sudah 5x.
     */
    public function incrementFailedLogin(): void
    {
        $this->increment('failed_login_attempts');

        if ($this->failed_login_attempts >= 5) {
            $this->update([
                'is_locked' => true,
                'locked_until' => now()->addMinutes(15),
            ]);
        }
    }

    /**
     * Reset counter gagal login setelah login berhasil.
     */
    public function resetFailedLogin(): void
    {
        $this->update([
            'failed_login_attempts' => 0,
            'is_locked' => false,
            'locked_until' => null,
        ]);
    }

    /**
     * Generate password default dari tanggal lahir (DDMMYYYY).
     */
    public function generateDefaultPassword(): string
    {
        return $this->tanggal_lahir
            ? $this->tanggal_lahir->format('dmY')
            : 'admin123';
    }

    /**
     * Cek apakah anggota termasuk BPI (Badan Pengurus Inti).
     */
    public function isBpi(): bool
    {
        return Jabatan::isBpi($this->jabatan_struktural);
    }

    /**
     * Cek apakah anggota adalah Kepala Divisi.
     */
    public function isKadiv(): bool
    {
        return Jabatan::isKadiv($this->jabatan_struktural);
    }

    /**
     * Cek apakah anggota adalah Kepala Unit.
     */
    public function isKanit(): bool
    {
        return Jabatan::isKanit($this->jabatan_struktural);
    }
}
