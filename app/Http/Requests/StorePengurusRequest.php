<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengurusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'warga_id' => ['required'],
            'jabatan_id' => ['required', 'array'],
            'created_by' => ['nullable', 'numeric'],
            'updated_by' => ['nullable', 'numeric'],
        ];
    }
}
