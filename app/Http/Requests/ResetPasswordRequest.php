<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: Reset Password Anggota (oleh Super Admin)
 * Mendukung password custom dengan default '12345678'.
 */
class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasRole('super_admin') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'password' => 'nullable|string|min:4|max:50',
        ];
    }

    /**
     * Custom messages.
     */
    public function messages(): array
    {
        return [
            'password.min' => 'Password minimal 4 karakter.',
            'password.max' => 'Password maksimal 50 karakter.',
        ];
    }

    /**
     * Get the password value — defaults to '12345678' if empty.
     */
    public function getPasswordValue(): string
    {
        return $this->input('password') ?: '12345678';
    }
}
