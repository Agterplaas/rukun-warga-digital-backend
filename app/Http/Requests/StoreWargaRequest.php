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
            'data.*.no_kk' => ['nullable', 'numeric'],
            'data.*.nik' => ['required', 'numeric'],
            'data.*.nama' => ['required', 'required_without_all:no_kk,nik'],
            'data.*.jenis_kelamin' => ['required', 'required_without_all:no_kk,nik'],
            'data.*.tgl_lahir' => ['required', 'required_without_all:no_kk,nik'],
            'data.*.alamat_ktp' => ['required', 'required_without_all:no_kk,nik'],
            'data.*.blok' => ['required', 'required_without_all:no_kk,nik'],
            'data.*.nomor' => ['required', 'numeric', 'required_without_all:no_kk,nik'],
            'data.*.rt' => ['required', 'numeric', 'required_without_all:no_kk,nik'],
            'data.*.agama' => ['required', 'numeric', 'required_without_all:no_kk,nik'],
            'data.*.no_telp' => ['nullable', 'numeric', 'required_without_all:no_kk,nik'],
            'data.*.status_pekerjaan' => ['required', 'required_without_all:no_kk,nik'],
            'data.*.pekerjaan' => ['nullable', 'required_without_all:no_kk,nik'],
            'data.*.status_warga' => ['required', 'numeric', 'required_without_all:no_kk,nik'],
            'data.*.status_kawin' => ['required', 'numeric', 'required_without_all:no_kk,nik'],
            'data.*.status_sosial' => ['required', 'numeric', 'required_without_all:no_kk,nik'],
            'data.*.catatan' => ['nullable', 'required_without_all:no_kk,nik'],
            'data.*.kk_pj' => ['required', 'required_without_all:no_kk,nik'],
        ];
    }
}
