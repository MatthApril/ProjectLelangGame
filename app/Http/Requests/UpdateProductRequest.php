<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'product_name' => 'required|string|max:255',
            'description' => 'required|string',
            'product_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stok' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,category_id',
            'game_id' => 'required|exists:games,game_id',
        ];
    }

    public function messages(): array
    {
        return [
            'product_name.required' => 'Nama produk wajib diisi.',
            'product_name.max' => 'Nama produk maksimal 255 karakter.',
            'description.required' => 'Deskripsi produk wajib diisi.',
            'product_img.image' => 'File harus berupa gambar.',
            'product_img.mimes' => 'Format gambar harus: jpeg, png, atau jpg.',
            'product_img.max' => 'Ukuran gambar maksimal 2MB.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok minimal 0.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga minimal 0.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'game_id.required' => 'Game wajib dipilih.',
            'game_id.exists' => 'Game tidak valid.',
        ];
    }
}
