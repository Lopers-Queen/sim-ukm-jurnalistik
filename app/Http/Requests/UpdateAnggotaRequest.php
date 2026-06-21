<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request: Update Anggota (FR-02)
 */
class UpdateAnggotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('organisasi.edit');
    }

    public function rules(): array
    {
        $anggotaId = $this->route('anggotum')->id;

        return [
            'nim'                => ['required', 'string', 'max:20', Rule::unique('anggota', 'nim')->ignore($anggotaId)],
            'nama_lengkap'       => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email', 'max:255', Rule::unique('anggota', 'email')->ignore($anggotaId)],
            'tanggal_lahir'      => ['required', 'date', 'before:today'],
            'tempat_lahir'       => ['nullable', 'string', 'max:100'],
            'jenis_kelamin'      => ['required', 'in:L,P'],
            'no_hp'              => ['nullable', 'string', 'max:20'],
            'alamat'             => ['nullable', 'string'],
            'program_studi'      => ['nullable', 'string', 'max:100'],
            'jurusan'            => ['nullable', 'string', 'max:100'],
            'foto_profil'        => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'divisi'             => ['nullable', 'in:fotografi,pers_penyiaran,videografi,kominfo,redaksi,inventory'],
            'jabatan_struktural' => ['required', 'in:ketua_umum,wakil_ketua_umum,sekretaris_umum_1,sekretaris_umum_2,bendahara_umum_1,bendahara_umum_2,kadiv_fotografi,kadiv_pers_penyiaran,kadiv_videografi,kanit_kominfo,kanit_redaksi,kanit_inventory,staf,anggota'],
            'status_keanggotaan' => ['required', 'in:aktif,pasif,alumni'],
        ];
    }

    public function messages(): array
    {
        return [
            'nim.required'           => 'NIM wajib diisi.',
            'nim.unique'             => 'NIM sudah terdaftar.',
            'nama_lengkap.required'  => 'Nama lengkap wajib diisi.',
            'email.unique'           => 'Email sudah terdaftar.',
        ];
    }
}
