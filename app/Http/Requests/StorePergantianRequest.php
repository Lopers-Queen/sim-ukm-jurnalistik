<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: Store Pergantian Kepengurusan (FR-17)
 */
class StorePergantianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('pergantian.create');
    }

    public function rules(): array
    {
        return [
            'nama_periode'    => ['required', 'string', 'max:20'],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
            'deskripsi'       => ['nullable', 'string'],
            // BPI positions
            'ketua_umum'           => ['required', 'exists:anggota,id'],
            'wakil_ketua_umum'     => ['required', 'exists:anggota,id'],
            'sekretaris_umum_1'    => ['required', 'exists:anggota,id'],
            'sekretaris_umum_2'    => ['required', 'exists:anggota,id'],
            'bendahara_umum_1'     => ['required', 'exists:anggota,id'],
            'bendahara_umum_2'     => ['required', 'exists:anggota,id'],
            // Kadiv
            'kadiv_fotografi'      => ['required', 'exists:anggota,id'],
            'kadiv_pers_penyiaran' => ['required', 'exists:anggota,id'],
            'kadiv_videografi'     => ['required', 'exists:anggota,id'],
            // Kanit
            'kanit_kominfo'        => ['required', 'exists:anggota,id'],
            'kanit_redaksi'        => ['required', 'exists:anggota,id'],
            'kanit_inventory'      => ['required', 'exists:anggota,id'],
            // Override reasons
            'override_reasons'     => ['nullable', 'array'],
            'override_reasons.*'   => ['nullable', 'string', 'min:50'],
        ];
    }

    /**
     * Build the jabatan-to-anggota mapping from validated data.
     */
    public function jabatanMap(): array
    {
        return [
            'ketua_umum'           => $this->ketua_umum,
            'wakil_ketua_umum'     => $this->wakil_ketua_umum,
            'sekretaris_umum_1'    => $this->sekretaris_umum_1,
            'sekretaris_umum_2'    => $this->sekretaris_umum_2,
            'bendahara_umum_1'     => $this->bendahara_umum_1,
            'bendahara_umum_2'     => $this->bendahara_umum_2,
            'kadiv_fotografi'      => $this->kadiv_fotografi,
            'kadiv_pers_penyiaran' => $this->kadiv_pers_penyiaran,
            'kadiv_videografi'     => $this->kadiv_videografi,
            'kanit_kominfo'        => $this->kanit_kominfo,
            'kanit_redaksi'        => $this->kanit_redaksi,
            'kanit_inventory'      => $this->kanit_inventory,
        ];
    }
}
