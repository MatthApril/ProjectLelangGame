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
            'shop_img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'open_hour' => 'required|date_format:H:i',
            'close_hour' => 'required|date_format:H:i|after:open_hour',
        ];
    }

    public function messages(): array
    {
        return [
            'shop_name.required' => 'Nama Toko wajib diisi',
            'shop_name.max' => 'Nama Toko maksimal 255 karakter',
            'shop_img.required' => 'Gambar Toko wajib diupload',
            'shop_img.image' => 'File harus berupa gambar',
            'shop_img.mimes' => 'Format gambar harus: jpeg, png, atau jpg',
            'shop_img.max' => 'Ukuran gambar maksimal 2MB',
            'open_hour.required' => 'Jam buka wajib diisi',
            'open_hour.date_format' => 'Format jam buka tidak valid',
            'close_hour.required' => 'Jam tutup wajib diisi',
            'close_hour.date_format' => 'Format jam tutup tidak valid',
            'close_hour.after' => 'Jam tutup harus lebih lambat dari jam buka',
        ];
    }

}
