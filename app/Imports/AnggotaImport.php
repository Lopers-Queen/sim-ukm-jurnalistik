<?php

namespace App\Imports;

use App\Models\Anggota;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

/**
 * Import Anggota dari file Excel/CSV.
 *
 * Format kolom wajib:
 *   nim, nama_lengkap, email, tanggal_lahir, jenis_kelamin
 *
 * Format kolom opsional:
 *   tempat_lahir, no_hp, alamat, program_studi, jurusan,
 *   divisi, jabatan_struktural, status_keanggotaan, tanggal_bergabung
 */
class AnggotaImport implements ToCollection, WithHeadingRow
{
    public array $errors = [];
    public int $successCount = 0;
    public int $skipCount = 0;
    public array $skippedRows = [];

    /**
     * Map jabatan yang diterima (case-insensitive, human-readable → db value)
     */
    private array $jabatanMap = [
        'ketua umum'              => 'ketua_umum',
        'wakil ketua umum'        => 'wakil_ketua_umum',
        'sekretaris umum 1'       => 'sekretaris_umum_1',
        'sekretaris umum 2'       => 'sekretaris_umum_2',
        'bendahara umum 1'        => 'bendahara_umum_1',
        'bendahara umum 2'        => 'bendahara_umum_2',
        'kadiv fotografi'         => 'kadiv_fotografi',
        'kadiv pers penyiaran'    => 'kadiv_pers_penyiaran',
        'kadiv videografi'        => 'kadiv_videografi',
        'kanit kominfo'           => 'kanit_kominfo',
        'kanit redaksi'           => 'kanit_redaksi',
        'kanit inventory'         => 'kanit_inventory',
        'staf'                    => 'staf',
        'anggota'                 => 'anggota',
    ];

    private array $divisiMap = [
        'fotografi'        => 'fotografi',
        'pers penyiaran'   => 'pers_penyiaran',
        'pers & penyiaran' => 'pers_penyiaran',
        'videografi'       => 'videografi',
        'kominfo'          => 'kominfo',
        'redaksi'          => 'redaksi',
        'inventory'        => 'inventory',
    ];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 karena heading row = 1, index mulai dari 0

            // Skip baris kosong
            if (empty($row['nim']) && empty($row['nama_lengkap'])) {
                continue;
            }

            // Normalize data
            $nim = trim($row['nim'] ?? '');
            $namaLengkap = trim($row['nama_lengkap'] ?? '');
            $email = trim($row['email'] ?? '');
            $tanggalLahirRaw = trim($row['tanggal_lahir'] ?? '');
            $jenisKelamin = strtoupper(trim($row['jenis_kelamin'] ?? ''));

            // Parse tanggal lahir (multiple format)
            $tanggalLahir = $this->parseTanggalLahir($tanggalLahirRaw);

            // Normalize jabatan & divisi
            $jabatan = $this->normalizeJabatan(trim($row['jabatan_struktural'] ?? 'anggota'));
            $divisi = $this->normalizeDivisi(trim($row['divisi'] ?? ''));
            $status = strtolower(trim($row['status_keanggotaan'] ?? 'aktif'));

            // Validasi baris
            $validator = Validator::make([
                'nim'                => $nim,
                'nama_lengkap'       => $namaLengkap,
                'email'              => $email,
                'tanggal_lahir'      => $tanggalLahir,
                'jenis_kelamin'      => $jenisKelamin,
                'jabatan_struktural' => $jabatan,
                'status_keanggotaan' => $status,
            ], [
                'nim'                => 'required|string|max:20',
                'nama_lengkap'       => 'required|string|max:255',
                'email'              => 'required|email|max:255',
                'tanggal_lahir'      => 'required|date|before:today',
                'jenis_kelamin'      => 'required|in:L,P',
                'jabatan_struktural' => 'required|in:ketua_umum,wakil_ketua_umum,sekretaris_umum_1,sekretaris_umum_2,bendahara_umum_1,bendahara_umum_2,kadiv_fotografi,kadiv_pers_penyiaran,kadiv_videografi,kanit_kominfo,kanit_redaksi,kanit_inventory,staf,anggota',
                'status_keanggotaan' => 'required|in:aktif,pasif,alumni',
            ]);

            if ($validator->fails()) {
                $this->errors[] = "Baris {$rowNumber} ({$nim} - {$namaLengkap}): " . implode(', ', $validator->errors()->all());
                $this->skipCount++;
                $this->skippedRows[] = $rowNumber;
                continue;
            }

            // Skip jika NIM atau email sudah ada
            if (Anggota::withTrashed()->where('nim', $nim)->exists()) {
                $this->errors[] = "Baris {$rowNumber}: NIM '{$nim}' sudah terdaftar — dilewati.";
                $this->skipCount++;
                $this->skippedRows[] = $rowNumber;
                continue;
            }
            if (Anggota::withTrashed()->where('email', $email)->exists()) {
                $this->errors[] = "Baris {$rowNumber}: Email '{$email}' sudah terdaftar — dilewati.";
                $this->skipCount++;
                $this->skippedRows[] = $rowNumber;
                continue;
            }

            // Parse tanggal bergabung
            $tanggalBergabung = $this->parseTanggalLahir(trim($row['tanggal_bergabung'] ?? ''));

            // Create anggota
            $anggota = Anggota::create([
                'nim'                => $nim,
                'nama_lengkap'       => $namaLengkap,
                'email'              => $email,
                'password'           => Hash::make(Carbon::parse($tanggalLahir)->format('dmY')),
                'tanggal_lahir'      => $tanggalLahir,
                'tempat_lahir'       => trim($row['tempat_lahir'] ?? '') ?: null,
                'jenis_kelamin'      => $jenisKelamin,
                'no_hp'              => trim($row['no_hp'] ?? '') ?: null,
                'alamat'             => trim($row['alamat'] ?? '') ?: null,
                'program_studi'      => trim($row['program_studi'] ?? '') ?: null,
                'jurusan'            => trim($row['jurusan'] ?? '') ?: null,
                'divisi'             => $divisi ?: null,
                'jabatan_struktural' => $jabatan,
                'status_keanggotaan' => $status,
                'tanggal_bergabung'  => $tanggalBergabung ?: now()->toDateString(),
                'is_first_login'     => true,
            ]);

            // Auto-assign role
            $this->assignRoleByJabatan($anggota);

            $this->successCount++;
        }
    }

    /**
     * Parse tanggal lahir dari berbagai format.
     */
    private function parseTanggalLahir(string $value): ?string
    {
        if (empty($value)) return null;

        // Jika angka Excel serial date
        if (is_numeric($value)) {
            try {
                return Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int) $value)
                )->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        // Coba berbagai format tanggal
        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y', 'dmY', 'Y/m/d'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
        }

        // Fallback ke Carbon::parse
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function normalizeJabatan(string $value): string
    {
        $lower = strtolower($value);
        return $this->jabatanMap[$lower] ?? $value;
    }

    private function normalizeDivisi(string $value): ?string
    {
        if (empty($value)) return null;
        $lower = strtolower($value);
        return $this->divisiMap[$lower] ?? $value;
    }

    private function assignRoleByJabatan(Anggota $anggota): void
    {
        $roleMap = [
            'ketua_umum'           => 'ketua_umum',
            'wakil_ketua_umum'     => 'wakil_ketua_umum',
            'sekretaris_umum_1'    => 'sekretaris_umum_1',
            'sekretaris_umum_2'    => 'sekretaris_umum_2',
            'bendahara_umum_1'     => 'bendahara_umum_1',
            'bendahara_umum_2'     => 'bendahara_umum_2',
            'kadiv_fotografi'      => 'kadiv_fotografi',
            'kadiv_pers_penyiaran' => 'kadiv_pers_penyiaran',
            'kadiv_videografi'     => 'kadiv_videografi',
            'kanit_kominfo'        => 'kanit_kominfo',
            'kanit_redaksi'        => 'kanit_redaksi',
            'kanit_inventory'      => 'kanit_inventory',
            'staf'                 => 'staf',
            'anggota'              => 'anggota_aktif',
        ];

        $roleName = $roleMap[$anggota->jabatan_struktural] ?? 'anggota_aktif';

        if ($anggota->status_keanggotaan === 'pasif') {
            $roleName = 'anggota_pasif';
        }

        $anggota->assignRole($roleName);
    }
}
