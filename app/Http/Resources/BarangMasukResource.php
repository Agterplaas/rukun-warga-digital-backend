<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarangMasukResource extends JsonResource
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
			'role_id' => $this->role_id,
			'jenis_barang' => $this->jenis_barang,
			'jml_barang' => $this->jml_barang,
			'catatan' => $this->catatan,
			'storage' => $this->storage,
			'kepemilikan' => $this->kepemilikan,
			'created_by' => $this->created_by,
			'updated_by' => $this->updated_by,
			'deleted_at' => $this->deleted_at,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
    }
}
