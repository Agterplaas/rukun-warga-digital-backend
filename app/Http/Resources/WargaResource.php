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
use Carbon\Carbon;
use DateTime;
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

        $tgl_lahir = new DateTime($this->tgl_lahir);
        $tgl_sekarang = new DateTime();
        $selisih = $tgl_lahir->diff($tgl_sekarang);
        $usia = $selisih->y;

        $tglLahir = $this->tgl_lahir;
        $carbonTglLahir = Carbon::parse($tglLahir);
        $namaBulan = BulanIndonesiaEnum::label($carbonTglLahir->month);
        $tanggalFormatted = $carbonTglLahir->format('d').' '.$namaBulan.' '.$carbonTglLahir->format('Y');

        return [
            'id' => $this->id,
            'no_kk' => decrypt($this->no_kk),
            'nik' => decrypt($this->nik),
            'nama' => $this->nama,
            'jenis_kelamin' => JenisKelaminEnum::label($this->jenis_kelamin),
            'tgl_lahir' => $tanggalFormatted,
            'usia' => $usia,
            'alamat_ktp' => $this->alamat_ktp,
            'blok' => strtoupper($this->blok),
            'nomor' => $this->nomor,
            'rt' => ltrim($this->rt, '0'),
            'agama' => AgamaEnum::label($this->agama),
            'no_telp' => $this->no_telp,
            'status_pekerjaan' => StatusPekerjaanEnum::label($this->status_pekerjaan),
            'pekerjaan' => $this->pekerjaan,
            'status_warga' => StatusWargaEnum::label($this->status_warga),
            'status_kawin' => StatusKawinEnum::label($this->status_kawin),
            'status_sosial' => StatusSosialEnum::label($this->status_sosial),
            'catatan' => $this->catatan,
            'kk_pj' => StatusKKPJEnum::label($this->kk_pj),
        ];
    }
}
