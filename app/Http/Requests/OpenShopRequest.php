<?php

namespace App\Http\Requests;

use App\Rules\EmailRegisteredRule;
use App\Rules\ShopNameExist;
use App\Rules\UsernameExistRule;
use Illuminate\Foundation\Http\FormRequest;

class OpenShopRequest extends FormRequest
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
            'shop_name' => ['required', 'string', new ShopNameExist],
        ];
    }

    public function messages(): array
    {
        return [
            'shop_name.required' => 'Nama Toko Wajib Diisi!',
        ];
    }

}
