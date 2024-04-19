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
        $tglLahirDekripsi = decrypt($this->tgl_lahir);
        $tgl_lahir = new DateTime($tglLahirDekripsi);
        $tgl_sekarang = new DateTime();
        $selisih = $tgl_lahir->diff($tgl_sekarang);
        $usia = $selisih->y;
    
        // Tentukan kategori umur berdasarkan usia
        if ($usia <= 4) {
            $kategori_umur = 'Balita';
        } elseif ($usia >= 5 && $usia <= 12) {
            $kategori_umur = 'Anak-anak';
        } elseif ($usia >= 13 && $usia <= 17) {
            $kategori_umur = 'Remaja';
        } elseif ($usia >= 18 && $usia <= 64) {
            $kategori_umur = 'Dewasa';
        } else {
            $kategori_umur = 'Lansia';
        }
    
        $carbonTglLahir = Carbon::parse($tgl_lahir);
        $namaBulan = BulanIndonesiaEnum::label($carbonTglLahir->month);
        $tanggalFormatted = $carbonTglLahir->format('d').' '.$namaBulan.' '.$carbonTglLahir->format('Y');
    
        return [
            'id' => $this->id,
            'no_kk' => decrypt($this->no_kk),
            'nik' => decrypt($this->nik),
            'nama' => decrypt($this->nama),
            'jenis_kelamin' => JenisKelaminEnum::label($this->jenis_kelamin),
            'tgl_lahir' => $tanggalFormatted,
            'tempat_lahir' => $this->tempat_lahir,
            'usia' => $usia,
            'kategori_umur' => $kategori_umur, // Tambahkan kategori umur ke dalam respon
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
