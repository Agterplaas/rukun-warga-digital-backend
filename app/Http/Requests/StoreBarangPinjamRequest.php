<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangPinjamRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
			'acara_id' => ['required'],
			'jenis_barang' => ['required', 'exists:barang_masuk,jenis_barang'],
			'jml_barang' => ['required'],
			'catatan' => ['required'],
			'storage' => ['required'],
			'kepemilikan' => ['required'],
		];
    }
}

