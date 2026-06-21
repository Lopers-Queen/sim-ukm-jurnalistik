<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: Store Periode Kepengurusan (FR-16)
 */
class StorePeriodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('periode.create');
    }

    public function rules(): array
    {
        return [
            'nama_periode'  => ['required', 'string', 'max:100', 'unique:periode_kepengurusan,nama_periode'],
            'tahun_mulai'   => ['required', 'integer', 'min:2000', 'max:2099'],
            'tahun_selesai' => ['required', 'integer', 'min:2000', 'max:2099', 'gte:tahun_mulai'],
            'status'        => ['required', 'in:aktif,selesai,upcoming'],
        ];
    }
}
