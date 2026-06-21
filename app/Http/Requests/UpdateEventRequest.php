<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: Update Event (FR-09)
 */
class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('event.edit');
    }

    public function rules(): array
    {
        return [
            'nama_event'      => ['required', 'string', 'max:255'],
            'deskripsi'       => ['nullable', 'string'],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'lokasi'          => ['nullable', 'string', 'max:255'],
            'status'          => ['required', 'in:draft,direncanakan,aktif,selesai,batal'],
            'pic_id'          => ['nullable', 'exists:anggota,id'],
            'anggaran_total'  => ['nullable', 'numeric', 'min:0'],
            'periode_id'      => ['nullable', 'exists:periode_kepengurusan,id'],
        ];
    }
}
