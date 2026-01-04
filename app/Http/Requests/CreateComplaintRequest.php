<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateComplaintRequest extends FormRequest
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
            'description' => 'required|string|min:20|max:1000',
            'proof_img' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }
    public function messages(): array
    {
        return [
            'description.required' => 'Deskripsi masalah wajib diisi',
            'description.min' => 'Deskripsi minimal 20 karakter',
            'description.max' => 'Deskripsi maksimal 1000 karakter',
            'evidence_image.required' => 'Bukti foto wajib diupload',
            'evidence_image.image' => 'File harus berupa gambar',
            'evidence_image.mimes' => 'Format gambar: jpeg, png, jpg',
            'evidence_image.max' => 'Ukuran gambar maksimal 2MB'
        ];
    }
}
