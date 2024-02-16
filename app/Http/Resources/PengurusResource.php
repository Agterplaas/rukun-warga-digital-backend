<?php

namespace App\Http\Resources;

use App\Enum\AgamaEnum;
use App\Enum\BulanIndonesiaEnum;
use App\Enum\JenisKelaminEnum;
use App\Enum\StatusKawinEnum;
use App\Enum\StatusKKPJEnum;
use App\Enum\StatusPekerjaanEnum;
use App\Enum\StatusSosialEnum;
use App\Enum\StatusWargaEnum;
use App\Models\MJabatan;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class PengurusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        if (! empty($this->jabatan_id)) {
            $jabatanID = json_decode($this->jabatan_id, true);
            $jabatanData = MJabatan::select('id', 'nama')->whereIn('id', $jabatanID)->get();
            if ($jabatanData->isNotEmpty($jabatanData)) {
                $jabatan = MJabatanResource::collection($jabatanData);
            }
        }

        $tgl_lahir = new DateTime($this->warga->tgl_lahir);
        $tgl_sekarang = new DateTime();
        $selisih = $tgl_lahir->diff($tgl_sekarang);
        $usia = $selisih->y;

        $tglLahir = $this->warga->tgl_lahir;
        $carbonTglLahir = Carbon::parse($tglLahir);
        $namaBulan = BulanIndonesiaEnum::label($carbonTglLahir->month);
        $tanggalFormatted = $carbonTglLahir->format('d').' '.$namaBulan.' '.$carbonTglLahir->format('Y');

        return [
            'id' => $this->id,
            'warga' => [
                'id' => $this->warga->id,
                'no_kk' => decrypt($this->warga->no_kk),
                'nik' => decrypt($this->warga->nik),
                'nama' => $this->warga->nama,
                'jenis_kelamin' => JenisKelaminEnum::label($this->warga->jenis_kelamin),
                'tgl_lahir' => $tanggalFormatted,
                'usia' => $usia,
                'alamat_ktp' => $this->warga->alamat_ktp,
                'blok' => strtoupper($this->warga->blok),
                'nomor' => $this->warga->nomor,
                'rt' => ltrim($this->warga->rt, '0'),
                'agama' => AgamaEnum::label($this->warga->agama),
                'no_telp' => $this->warga->no_telp,
                'status_pekerjaan' => StatusPekerjaanEnum::label($this->warga->status_pekerjaan),
                'pekerjaan' => $this->warga->pekerjaan,
                'status_warga' => StatusWargaEnum::label($this->warga->status_warga),
                'status_kawin' => StatusKawinEnum::label($this->warga->status_kawin),
                'status_sosial' => StatusSosialEnum::label($this->warga->status_sosial),
                'catatan' => $this->warga->catatan,
                'kk_pj' => StatusKKPJEnum::label($this->warga->kk_pj),
            ],
            'jabatan' => $jabatan,
        ];
    }
}
