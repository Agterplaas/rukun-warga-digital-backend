<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KategoriBarangResource extends JsonResource
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
			'nama' => $this->nama,
			'deleted_at' => $this->deleted_at,
			'created_by' => $this->created_by,
			'updated_by' => $this->updated_by,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
    }
}
