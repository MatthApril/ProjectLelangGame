<?php

namespace App\Http\Requests;

use App\Rules\GameNameUnique;
use Illuminate\Foundation\Http\FormRequest;

class InputGameRequest extends FormRequest
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
            'game_name' => [
                'required',
                'string',
                'max:255',
                new GameNameUnique()
            ],
            'game_img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,category_id'
        ];
    }

    public function messages(): array
    {
        return [
            'game_name.required' => 'Nama game wajib diisi.',
            'game_img.required' => 'Gambar game wajib diupload.',
            'game_img.image' => 'File harus berupa gambar.',
            'game_img.mimes' => 'Format gambar harus: jpeg, png, atau jpg.',
            'game_img.max' => 'Ukuran gambar maksimal 2MB.',
            'categories.required' => 'Minimal pilih 1 kategori.',
            'categories.min' => 'Minimal pilih 1 kategori.',
            'categories.*.exists' => 'Kategori tidak valid.'
        ];
    }
}
