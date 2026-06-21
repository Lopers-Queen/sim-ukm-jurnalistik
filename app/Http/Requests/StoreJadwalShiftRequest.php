<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: Store/Update Jadwal Shift (FR-06)
 */
class StoreJadwalShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('jadwal-shift.create');
    }

    public function rules(): array
    {
        return [
            'anggota_id' => ['required', 'exists:anggota,id'],
            'hari'       => ['required', 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu'],
        ];
    }
}
