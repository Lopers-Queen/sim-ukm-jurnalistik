<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Export class for the Anggota import template.
 */
class AnggotaTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function headings(): array
    {
        return [
            'nim',
            'nama_lengkap',
            'email',
            'tanggal_lahir',
            'tempat_lahir',
            'jenis_kelamin',
            'no_hp',
            'alamat',
            'program_studi',
            'jurusan',
            'divisi',
            'jabatan_struktural',
            'status_keanggotaan',
            'tanggal_bergabung',
        ];
    }

    public function array(): array
    {
        return [
            [
                '240001001',
                'Ahmad Rizky Pratama',
                'ahmad.rizky@polnes.ac.id',
                '15/05/2004',
                'Samarinda',
                'L',
                '081234567890',
                'Jl. Contoh No. 1',
                'Teknik Informatika',
                'Teknologi Informasi',
                'Fotografi',
                'Anggota',
                'aktif',
                '01/09/2024',
            ],
            [
                '240001002',
                'Siti Nurhaliza',
                'siti.nurhaliza@polnes.ac.id',
                '20/11/2005',
                'Balikpapan',
                'P',
                '089876543210',
                'Jl. Contoh No. 2',
                'Administrasi Bisnis',
                'Administrasi Niaga',
                'Pers Penyiaran',
                'Staf',
                'aktif',
                '01/09/2024',
            ],
            [
                '--- HAPUS BARIS INI ---',
                'WAJIB diisi',
                'WAJIB, format email',
                'WAJIB, DD/MM/YYYY',
                'Opsional',
                'WAJIB: L atau P',
                'Opsional',
                'Opsional',
                'Opsional',
                'Opsional',
                'Fotografi/Pers Penyiaran/Videografi/Kominfo/Redaksi/Inventory',
                'Anggota/Staf/Kadiv .../Kanit ...',
                'aktif/pasif/alumni',
                'Opsional, DD/MM/YYYY',
            ],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1A56DB'],
                ],
            ],
            4 => [
                'font' => ['italic' => true, 'color' => ['rgb' => '999999']],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, 'B' => 25, 'C' => 30, 'D' => 15,
            'E' => 15, 'F' => 15, 'G' => 18, 'H' => 25,
            'I' => 22, 'J' => 22, 'K' => 20, 'L' => 25,
            'M' => 18, 'N' => 18,
        ];
    }
}
