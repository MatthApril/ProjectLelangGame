<?php

namespace App\Http\Requests;

use App\Rules\CategoryNameUnique;
use Illuminate\Foundation\Http\FormRequest;

class InsertCategoryRequest extends FormRequest
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
            'category_name' => [
                'required',
                'string',
                'max:255',
                new CategoryNameUnique()
            ],
            'category_img' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }
    public function messages(): array
    {
        return [
            'category_name.required' => 'Nama kategori wajib diisi.',
            'category_name.max' => 'Nama kategori maksimal 255 karakter.',
            'category_img.required' => 'Gambar kategori wajib diupload.',
            'category_img.image' => 'File harus berupa gambar.',
            'category_img.mimes' => 'Format gambar harus: jpeg, png, atau jpg.',
            'category_img.max' => 'Ukuran gambar maksimal 2MB.'
        ];
    }
}
