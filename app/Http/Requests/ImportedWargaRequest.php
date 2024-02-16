<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportedWargaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'file_template' => ['required', 'mimes:xlsx', 'file', 'max:2048'],
        ];
    }
}
