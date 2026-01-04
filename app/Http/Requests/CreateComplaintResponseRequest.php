<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateComplaintResponseRequest extends FormRequest
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
            'message' => 'required|string|min:20|max:1000',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120'
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Pesan pembelaan wajib diisi',
            'message.min' => 'Pesan minimal 20 karakter',
            'message.max' => 'Pesan maksimal 1000 karakter',
            'attachment.file' => 'File tidak valid',
            'attachment.mimes' => 'Format file: jpeg, png, jpg, pdf',
            'attachment.max' => 'Ukuran file maksimal 5MB'
        ];
    }
}
