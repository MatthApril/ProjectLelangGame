<?php

namespace App\Http\Requests;

use App\Rules\CategoryNameUnique;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
        $categoryId = $this->route('category');

        return [
            'category_name' => [
                'required',
                'string',
                'max:255',
                new CategoryNameUnique($categoryId)
            ]
        ];
    }
    public function messages(): array
    {
        return [
            'category_name.required' => 'Nama kategori wajib diisi',
            'category_name.max' => 'Nama kategori maksimal 255 karakter'
        ];
    }
}
