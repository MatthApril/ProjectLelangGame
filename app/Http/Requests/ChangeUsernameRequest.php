<?php

namespace App\Http\Requests;

use App\Rules\UsernameExistRule;
use Illuminate\Foundation\Http\FormRequest;

class ChangeUsernameRequest extends FormRequest
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
            'username' => ['required', 'string', new UsernameExistRule],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi',
        ];
    }
}
