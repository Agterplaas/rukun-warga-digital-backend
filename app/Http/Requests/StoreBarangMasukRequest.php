<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangMasukRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
			'role_id' => ['required', 'nullable'],
			'jenis_barang' => ['required'],
			'jml_barang' => ['required'],
			'catatan' => ['required'],
			'storage' => ['required'],
			'kepemilikan' => ['required'],
		];
    }
}

