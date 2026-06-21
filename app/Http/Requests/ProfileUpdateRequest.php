<?php

namespace App\Http\Requests;

use App\Models\Anggota;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi update profil anggota (FR-03).
 */
class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(Anggota::class)->ignore($this->user()->id),
            ],
            'no_hp'   => ['nullable', 'string', 'max:20'],
            'alamat'  => ['nullable', 'string'],
        ];
    }
}
