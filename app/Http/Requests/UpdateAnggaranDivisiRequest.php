<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: Update Anggaran Divisi (FR-07)
 */
class UpdateAnggaranDivisiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('anggaran-divisi.edit');
    }

    public function rules(): array
    {
        return [
            'periode_id'      => ['required', 'exists:periode_kepengurusan,id'],
            'divisi'          => ['required', 'in:fotografi,pers_penyiaran,videografi,kominfo,redaksi,inventory,bpi'],
            'bulan'           => ['required', 'integer', 'min:1', 'max:12'],
            'tahun'           => ['required', 'integer', 'min:2020', 'max:2099'],
            'jumlah_anggaran' => ['required', 'numeric', 'min:0'],
            'jumlah_terpakai' => ['nullable', 'numeric', 'min:0'],
            'keterangan'      => ['nullable', 'string'],
        ];
    }
}
