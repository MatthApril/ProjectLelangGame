<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertTemplateRequest extends FormRequest
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
            'code_tag' => 'required|unique:notification_templates,code_tag',
            'category' => 'required',
            'trigger_type' => 'required',
            'title' => 'required',
            'subject' => 'required',
            'body' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'code_tag.required' => 'Kode tag wajib diisi',
            'code_tag.unique' => 'Kode tag sudah digunakan',
            'category.required' => 'Kategori wajib diisi',
            'trigger_type.required' => 'Tipe pemicu wajib diisi',
            'title.required' => 'Judul wajib diisi',
            'subject.required' => 'Subjek wajib diisi',
            'body.required' => 'Isi pesan wajib diisi',
        ];
    }
}
