<?php

namespace App\Http\Requests;

use App\Rules\EmailRegisteredRule;
use App\Rules\UsernameExistRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', new UsernameExistRule],
            'email' => ['required', 'email', new EmailRegisteredRule],
            'password' => ['required', 'string'],
            'confirm_password' => ['required', 'same:password'],
            'bank_account_number' => ['required', 'digits:10'],
            // 'terms' => ['required'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'confirm_password.required' => 'Konfirmasi password wajib diisi.',
            'confirm_password.same' => 'Konfirmasi password dengan password tidak sama.',
            'bank_account_number.required' => 'Nomor rekening wajib diisi.',
            'bank_account_number.digits' => 'Nomor rekening harus 10 digit.',
            // 'terms.required' => 'Anda harus menyetujui Syarat & Ketentuan untuk melanjutkan.',
        ];
    }
}
