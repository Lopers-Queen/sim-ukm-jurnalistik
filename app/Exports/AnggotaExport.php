<?php

namespace App\Exports;

use App\Models\Anggota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Export data anggota ke Excel (FR-12)
 */
class AnggotaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        protected ?string $divisi = null,
        protected ?string $status = null,
    ) {}

    public function collection()
    {
        $query = Anggota::query();
        if ($this->divisi) { $query->where('divisi', $this->divisi); }
        if ($this->status) { $query->where('status_keanggotaan', $this->status); }
        return $query->orderBy('nama_lengkap')->get();
    }

    public function headings(): array
    {
        return ['No', 'NIM', 'Nama Lengkap', 'Email', 'Jenis Kelamin', 'Program Studi',
                'Jurusan', 'Divisi', 'Jabatan', 'Status', 'Tgl Bergabung', 'No HP'];
    }

    public function map($anggota): array
    {
        static $no = 0;
        $no++;
        return [
            $no, $anggota->nim, $anggota->nama_lengkap, $anggota->email,
            $anggota->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $anggota->program_studi, $anggota->jurusan, $anggota->divisi_label,
            $anggota->jabatan_lengkap, ucfirst($anggota->status_keanggotaan),
            $anggota->tanggal_bergabung?->format('d/m/Y'), $anggota->no_hp,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true, 'size' => 11]]];
    }
}
