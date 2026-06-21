<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: Tambah Anggota Baru (FR-02)
 */
class StoreAnggotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('organisasi.create');
    }

    public function rules(): array
    {
        return [
            'nim'                => ['required', 'string', 'max:20', 'unique:anggota,nim'],
            'nama_lengkap'       => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email', 'max:255', 'unique:anggota,email'],
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
            'tanggal_bergabung'  => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'nim.required'           => 'NIM wajib diisi.',
            'nim.unique'             => 'NIM sudah terdaftar.',
            'nama_lengkap.required'  => 'Nama lengkap wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.unique'           => 'Email sudah terdaftar.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before'   => 'Tanggal lahir harus sebelum hari ini.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'foto_profil.max'        => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
