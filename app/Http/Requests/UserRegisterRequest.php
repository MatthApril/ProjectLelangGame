<?php

namespace App\Http\Requests;

use App\Rules\EmailRegisteredRule;
use App\Rules\UsernameExistRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRegisterRequest extends FormRequest
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
            'email' => ['required', 'string', new EmailRegisteredRule],
            'password' => ['required', 'string'],
            'confirm_password' => ['required', 'same:password'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi',
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
            'confirm_password.required' => 'Konfirmasi Password wajib diisi',
            'confirm_password.same' => 'Konfirmasi Password dengan Password tidak sama',
        ];
    }

}
