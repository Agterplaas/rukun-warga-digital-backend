<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WargaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
			'id' => $this->id,
			'no_kk' => $this->no_kk,
			'nik' => $this->nik,
			'nama' => $this->nama,
			'jenis_kelamin' => $this->jenis_kelamin,
			'tgl_lahir' => $this->tgl_lahir,
			'alamat_ktp' => $this->alamat_ktp,
			'blok' => $this->blok,
			'nomor' => $this->nomor,
			'rt' => $this->rt,
			'agama' => $this->agama,
			'pekerjaan' => $this->pekerjaan,
			'no_telp' => $this->no_telp,
			'status_warga' => $this->status_warga,
			'status_kawin' => $this->status_kawin,
			'status_sosial' => $this->status_sosial,
			'catatan' => $this->catatan,
			'kk_pj' => $this->kk_pj,
			'created_by' => $this->created_by,
			'updated_by' => $this->updated_by,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'deleted_at' => $this->deleted_at,
		];
    }
}
