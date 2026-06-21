<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: Update Naskah Redaksi (FR-08)
 */
class UpdateNaskahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('naskah-redaksi.edit');
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
