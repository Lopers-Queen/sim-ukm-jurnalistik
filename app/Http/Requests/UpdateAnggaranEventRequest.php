<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: Update Anggaran Event (FR-18)
 */
class UpdateAnggaranEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('anggaran-event.edit');
    }

    public function rules(): array
    {
        return [
            'event_id'          => ['required', 'exists:event,id'],
            'item'              => ['required', 'string', 'max:255'],
            'kategori'          => ['nullable', 'string', 'max:100'],
            'qty'               => ['required', 'integer', 'min:1'],
            'harga_satuan'      => ['required', 'numeric', 'min:0'],
            'jumlah_dianggarkan' => ['required', 'numeric', 'min:0'],
            'jumlah_realisasi'  => ['nullable', 'numeric', 'min:0'],
            'bukti_transaksi'   => ['nullable', 'string'],
            'keterangan'        => ['nullable', 'string'],
        ];
    }
}
