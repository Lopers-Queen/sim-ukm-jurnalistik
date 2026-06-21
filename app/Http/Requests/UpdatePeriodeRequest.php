<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: Update Periode Kepengurusan (FR-16)
 */
class UpdatePeriodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('periode.edit');
    }

    public function rules(): array
    {
        $periodeId = $this->route('periode')?->id;

        return [
            'nama_periode'  => ['required', 'string', 'max:100', 'unique:periode_kepengurusan,nama_periode,' . $periodeId],
            'tahun_mulai'   => ['required', 'integer', 'min:2000', 'max:2099'],
            'tahun_selesai' => ['required', 'integer', 'min:2000', 'max:2099', 'gte:tahun_mulai'],
            'status'        => ['required', 'in:aktif,selesai,upcoming'],
        ];
    }
}
