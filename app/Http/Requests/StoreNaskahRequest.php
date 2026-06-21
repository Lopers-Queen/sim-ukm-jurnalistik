<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: Store/Update Naskah Redaksi (FR-08)
 */
class StoreNaskahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('naskah-redaksi.create');
    }

    public function rules(): array
    {
        return [
            'judul'    => ['required', 'string', 'max:255'],
            'konten'   => ['required', 'string'],
            'kategori' => ['nullable', 'string', 'max:50'],
        ];
    }
}
