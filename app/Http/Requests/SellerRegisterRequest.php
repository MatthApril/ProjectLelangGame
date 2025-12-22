<?php

namespace App\Http\Requests;

use App\Rules\EmailRegisteredRule;
use App\Rules\ShopNameExist;
use App\Rules\UsernameExistRule;
use Illuminate\Foundation\Http\FormRequest;

class SellerRegisterRequest extends FormRequest
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
            'shop_name' => ['required', 'string', new ShopNameExist],
            'password' => ['required', 'string'],
            'confirm_password' => ['required', 'same:password'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi',
            'email.required' => 'Email wajib diisi',
            'shop_name.required' => 'Nama Toko wajib diisi',
            'password.required' => 'Password wajib diisi',
            'confirm_password.required' => 'Konfirmasi Password wajib diisi',
            'confirm_password.same' => 'Konfirmasi Password dengan Password tidak sama',
        ];
    }

}
