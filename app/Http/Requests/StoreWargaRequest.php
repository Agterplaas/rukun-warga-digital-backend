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
            'data.*.nama' => ['required'],
            'data.*.tgl_lahir' => ['required'],
            'data.*.tempat_lahir' => ['required', 'required_without_all:no_kk,nik,nama,tgl_lahir'],
            'data.*.jenis_kelamin' => ['required', 'required_without_all:no_kk,nik,nama,tgl_lahir'],
            'data.*.alamat_ktp' => ['required', 'required_without_all:no_kk,nik,nama,tgl_lahir'],
            'data.*.blok' => ['required', 'required_without_all:no_kk,nik,nama,tgl_lahir'],
            'data.*.nomor' => ['required', 'numeric', 'required_without_all:no_kk,nik,nama,tgl_lahir'],
            'data.*.rt' => ['required', 'numeric', 'required_without_all:no_kk,nik,nama,tgl_lahir'],
            'data.*.agama' => ['required', 'numeric', 'required_without_all:no_kk,nik,nama,tgl_lahir'],
            'data.*.no_telp' => ['nullable', 'numeric', 'required_without_all:no_kk,nik,nama,tgl_lahir'],
            'data.*.status_pekerjaan' => ['required', 'required_without_all:no_kk,nik,nama,tgl_lahir'],
            'data.*.pekerjaan' => ['nullable', 'required_without_all:no_kk,nik,nama,tgl_lahir'],
            'data.*.status_warga' => ['required', 'numeric', 'required_without_all:no_kk,nik,nama,tgl_lahir'],
            'data.*.status_kawin' => ['required', 'numeric', 'required_without_all:no_kk,nik,nama,tgl_lahir'],
            'data.*.status_sosial' => ['required', 'numeric', 'required_without_all:no_kk,nik,nama,tgl_lahir'],
            'data.*.catatan' => ['nullable', 'required_without_all:no_kk,nik,nama,tgl_lahir'],
            'data.*.kk_pj' => ['required', 'required_without_all:no_kk,nik,nama,tgl_lahir'],
        ];
    }
}
