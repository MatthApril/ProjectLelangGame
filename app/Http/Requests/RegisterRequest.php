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
            'terms' => ['required', 'accepted'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'username.required' => 'Username Wajib Diisi!',
            'email.required' => 'Email Wajib Diisi!',
            'email.email' => 'Format Email Tidak Valid!',
            'password.required' => 'Password Wajib Diisi!',
            'confirm_password.required' => 'Konfirmasi Password Wajib Diisi!',
            'confirm_password.same' => 'Konfirmasi Password Dengan Password Tidak Sama!',
            'bank_account_number.required' => 'Nomor Rekening Wajib Diisi!',
            'bank_account_number.digits' => 'Nomor Rekening Harus 10 Digit!',
            'terms.required' => 'Anda harus menyetujui Syarat & Ketentuan untuk melanjutkan.',
            'terms.accepted' => 'Anda harus menyetujui Syarat & Ketentuan untuk melanjutkan.',
        ];
    }

}
