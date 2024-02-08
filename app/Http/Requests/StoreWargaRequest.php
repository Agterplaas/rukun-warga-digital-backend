<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWargaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
			'no_kk' => ['nullable', 'numeric'],
			'nik' => ['required', 'numeric'],
			'nama' => ['nullable'],
			'jenis_kelamin' => ['nullable'],
			'tgl_lahir' => ['nullable'],
			'alamat_ktp' => ['required'],
			'blok' => ['required'],
			'nomor' => ['required', 'numeric'],
			'rt' => ['required', 'numeric'],
			'agama' => ['nullable'],
			'pekerjaan' => ['nullable'],
			'no_telp' => ['nullable', 'numeric'],
			'status_warga' => ['nullable'],
			'status_kawin' => ['nullable'],
			'status_sosial' => ['nullable'],
			'catatan' => ['nullable'],
			'kk_pj' => ['nullable'],
		];
    }
}

