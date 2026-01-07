<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTemplateRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'trigger_type' => 'required|in:transactional,broadcast',
            'category' => 'required|in:system,order',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul wajib diisi.',
            'title.string' => 'Judul harus berupa string.',
            'title.max' => 'Judul maksimal 255 karakter.',

            'subject.required' => 'Subjek wajib diisi.',
            'subject.string' => 'Subjek harus berupa string.',
            'subject.max' => 'Subjek maksimal 255 karakter.',

            'body.required' => 'Isi pesan wajib diisi.',
            'body.string' => 'Isi pesan harus berupa string.',

            'trigger_type.required' => 'Tipe pemicu wajib diisi.',
            'trigger_type.in' => 'Tipe pemicu tidak valid.',

            'category.required' => 'Kategori wajib diisi.',
            'category.in' => 'Kategori tidak valid.',
        ];
    }
}
