<?php

namespace App\Http\Controllers;

use App\Enum\AgamaEnum;
use App\Enum\JenisKelaminEnum;
use App\Enum\StatusKawinEnum;
use App\Enum\StatusKKPJEnum;
use App\Enum\StatusPekerjaanEnum;
use App\Enum\StatusSosialEnum;
use App\Enum\StatusWargaEnum;
use App\Models\Warga;
use Illuminate\Support\Facades\DB;

class StaticWargaController extends Controller
{
    /**
     * @OA\Get(
     *      path="/statistik-umur",
     *      tags={"Statistic Warga"},
     *      summary="Statistic Umur Warga",
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function hitungStatistikUmur()
    {
        $warga = Warga::orderBy('rt')->get();

        $jumlahWarga = $warga->count();
        $totalUsia = 0;

        $jumlahPerKelompokUsia = [
            'balita' => 0,
            'anak_anak' => 0,
            'remaja' => 0,
            'dewasa' => 0,
            'lansia' => 0,
        ];

        $dataPerRT = [];

        foreach ($warga as $orang) {
            $tgl_lahir = new \DateTime($orang->tgl_lahir);
            $tgl_sekarang = new \DateTime();
            $selisih = $tgl_lahir->diff($tgl_sekarang);
            $usia = $selisih->y;

            $totalUsia += $usia;

            if ($usia <= 4) {
                $kelompokUsia = 'balita';
            } elseif ($usia >= 5 && $usia <= 12) {
                $kelompokUsia = 'anak_anak';
            } elseif ($usia >= 13 && $usia <= 17) {
                $kelompokUsia = 'remaja';
            } elseif ($usia >= 18 && $usia <= 64) {
                $kelompokUsia = 'dewasa';
            } else {
                $kelompokUsia = 'lansia';
            }

            $jumlahPerKelompokUsia[$kelompokUsia]++;
            $rt = 'rt_'.$orang->rt;

            if (! isset($dataPerRT[$rt])) {
                $dataPerRT[$rt] = [
                    'jumlah_per_kelompok_usia' => [
                        'balita' => 0,
                        'anak_anak' => 0,
                        'remaja' => 0,
                        'dewasa' => 0,
                        'lansia' => 0,
                    ],
                    'rata_rata_usia' => 0,
                ];
            }

            $dataPerRT[$rt]['jumlah_per_kelompok_usia'][$kelompokUsia]++;
            $dataPerRT[$rt]['rata_rata_usia'] += $usia;
        }

        foreach ($dataPerRT as $rt => $data) {
            $jumlahWargaRT = array_sum($data['jumlah_per_kelompok_usia']);
            $dataPerRT[$rt]['rata_rata_usia'] = round($data['rata_rata_usia'] / $jumlahWargaRT, 0);
        }

        $persentasePerKelompokUsiaPerRT = [];
        foreach ($dataPerRT as $rt => $data) {
            $persentasePerRT = [];
            foreach ($data['jumlah_per_kelompok_usia'] as $kelompokUsia => $jumlah) {
                $persentase = round(($jumlah / array_sum($data['jumlah_per_kelompok_usia'])) * 100, 2);
                $persentasePerRT[$kelompokUsia] = $persentase;
            }
            $persentasePerKelompokUsiaPerRT[$rt] = $persentasePerRT;
        }

        $jumlahKeseluruhanPerKelompokUsia = [];
        foreach ($jumlahPerKelompokUsia as $kelompokUsia => $jumlah) {
            $jumlahKeseluruhanPerKelompokUsia[$kelompokUsia] = $jumlah;
        }

        $persentaseKeseluruhanPerKelompokUsia = [];
        foreach ($jumlahPerKelompokUsia as $kelompokUsia => $jumlah) {
            $persentase = round(($jumlah / $jumlahWarga) * 100, 2);
            $persentaseKeseluruhanPerKelompokUsia[$kelompokUsia] = $persentase;
        }

        $rataRataUsiaKeseluruhan = round($totalUsia / $jumlahWarga, 0);

        return [
            'jumlah_warga' => $jumlahWarga,
            'jumlah_kelompok_per_rt' => $dataPerRT,
            'jumlah_persentase_kelompok_per_rt' => $persentasePerKelompokUsiaPerRT,
            'jumlah_keseluruhan_per_kelompok_usia' => $jumlahKeseluruhanPerKelompokUsia,
            'persentase_keseluruhan_per_kelompok_usia' => $persentaseKeseluruhanPerKelompokUsia,
            'rata_rata_usia_keseluruhan' => $rataRataUsiaKeseluruhan,
        ];
    }

    /**
     * @OA\Get(
     *      path="/statistik-warga-rt",
     *      tags={"Statistic Warga"},
     *      summary="Statistic Jumlah Warga",
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function hitungWargaRT()
    {
        $residentsByRT = Warga::select('rt', DB::raw('count(*) as total_warga'))
            ->groupBy('rt')
            ->orderBy('rt')
            ->get();

        $totalResidents = Warga::count();

        $response = [
            'total_warga_keseluruhan' => $totalResidents,
            'data_per_rt' => [],
        ];

        foreach ($residentsByRT as $resident) {
            $response['data_per_rt']['rt_'.sprintf('%02d', $resident->rt)] = [
                'total_warga' => $resident->total_warga,
            ];
        }

        return response()->json($response);
    }

     /**
     * @OA\Get(
     *      path="/statistik-agama-warga",
     *      tags={"Statistic Warga"},
     *      summary="Statistic Jumlah Agama Warga",
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function hitungAgamaPerRt()
    {
        $agamaCountsPerRt = [];
        $totalAgamaCounts = [];

        $wargas = Warga::all();

        foreach ($wargas as $warga) {
            $rt = 'rt_'.$warga->rt;
            $agama = strtolower(AgamaEnum::label($warga->agama));
            $agama = str_replace(' ', '_', $agama);

            if (! isset($agamaCountsPerRt[$rt])) {
                $agamaCountsPerRt[$rt] = [];
            }

            if (! isset($agamaCountsPerRt[$rt][$agama])) {
                $agamaCountsPerRt[$rt][$agama] = 0;
            }

            $agamaCountsPerRt[$rt][$agama]++;

            if (! isset($totalAgamaCounts[$agama])) {
                $totalAgamaCounts[$agama] = 0;
            }

            $totalAgamaCounts[$agama]++;
        }

        ksort($agamaCountsPerRt);

        return [
            'agama_per_rt' => $agamaCountsPerRt,
            'total_agama' => $totalAgamaCounts,
        ];
    }

    /**
     * @OA\Get(
     *      path="/statistik-jenis-kelamin-warga",
     *      tags={"Statistic Warga"},
     *      summary="Statistic Jumlah Jenis Kelamin Warga",
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function hitungJumlahJenisKelaminPerRT()
    {
        $jenisKelaminPerRT = [];
        $totalKeseluruhan = [
            JenisKelaminEnum::LAKI_LAKI => 0,
            JenisKelaminEnum::PEREMPUAN => 0,
        ];

        $wargas = Warga::orderBy('rt')->get();

        foreach ($wargas as $warga) {
            $jenisKelamin = $warga->jenis_kelamin;
            $rt = $warga->rt;

            if (! isset($jenisKelaminPerRT["rt_$rt"])) {
                $jenisKelaminPerRT["rt_$rt"] = [
                    JenisKelaminEnum::LAKI_LAKI => 0,
                    JenisKelaminEnum::PEREMPUAN => 0,
                ];
            }

            if ($jenisKelamin == JenisKelaminEnum::LAKI_LAKI) {
                $jenisKelaminPerRT["rt_$rt"][JenisKelaminEnum::LAKI_LAKI]++;
                $totalKeseluruhan[JenisKelaminEnum::LAKI_LAKI]++;
            } elseif ($jenisKelamin == JenisKelaminEnum::PEREMPUAN) {
                $jenisKelaminPerRT["rt_$rt"][JenisKelaminEnum::PEREMPUAN]++;
                $totalKeseluruhan[JenisKelaminEnum::PEREMPUAN]++;
            }
        }

        $totalJenisKelaminPerRT = [];
        foreach ($jenisKelaminPerRT as $rt => $jumlahJenisKelamin) {
            $totalJenisKelaminPerRT[$rt] = [
                'laki_laki' => $jumlahJenisKelamin[JenisKelaminEnum::LAKI_LAKI],
                'perempuan' => $jumlahJenisKelamin[JenisKelaminEnum::PEREMPUAN],
            ];
        }

        return [
            'jenis_kelamin_per_rt' => $totalJenisKelaminPerRT,
            'total_keseluruhan' => [
                'laki_laki' => $totalKeseluruhan[JenisKelaminEnum::LAKI_LAKI],
                'perempuan' => $totalKeseluruhan[JenisKelaminEnum::PEREMPUAN],
            ],
        ];
    }

    /**
     * @OA\Get(
     *      path="/statistik-jenis-kawin-warga",
     *      tags={"Statistic Warga"},
     *      summary="Statistic Jumlah Jenis Status Kawin Warga",
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function hitungStatusKawinPerRT()
    {
        $statusCounts = Warga::select('rt', 'status_kawin', DB::raw('count(*) as total'))
            ->groupBy('rt', 'status_kawin')
            ->orderBy('rt')
            ->get();

        $result = [];
        $totals = [
            'belum_kawin' => 0,
            'kawin' => 0,
            'cerai' => 0,
            'janda' => 0,
            'duda' => 0,
        ];

        foreach ($statusCounts as $statusCount) {
            $rt = 'rt_'.$statusCount->rt;
            $status = str_replace(' ', '_', strtolower(StatusKawinEnum::label($statusCount->status_kawin))); // Mengubah spasi menjadi garis bawah
            $total = $statusCount->total;

            if (! isset($result[$rt])) {
                $result[$rt] = [];
            }

            $result[$rt][$status] = $total;
            switch ($statusCount->status_kawin) {
                case StatusKawinEnum::BELUM_KAWIN:
                    $totals['belum_kawin'] += $total;
                    break;
                case StatusKawinEnum::KAWIN:
                    $totals['kawin'] += $total;
                    break;
                case StatusKawinEnum::CERAI:
                    $totals['cerai'] += $total;
                    break;
                case StatusKawinEnum::JANDA:
                    $totals['janda'] += $total;
                    break;
                case StatusKawinEnum::DUDA:
                    $totals['duda'] += $total;
                    break;
            }
        }

        $totals = array_map(function ($value) {
            return str_replace(' ', '_', $value);
        }, $totals);

        $result['total_keseluruhan'] = $totals;

        return $result;
    }

     /**
     * @OA\Get(
     *      path="/statistik-kk-pj-warga",
     *      tags={"Statistic Warga"},
     *      summary="Statistic Jumlah Jenis Anggota Warga",
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function hitungStatusAnggotaPerRT()
    {
        $statusCounts = Warga::select('rt', 'kk_pj', DB::raw('count(*) as total'))
            ->groupBy('rt', 'kk_pj')
            ->orderBy('rt')
            ->get();

        $result = [];
        $totals = [
            StatusKKPJEnum::ANGGOTA => 0,
            StatusKKPJEnum::KEPALA_KELUARGA => 0,
            StatusKKPJEnum::PENANGGUNG_JAWAB => 0,
        ];

        foreach ($statusCounts as $statusCount) {
            $rt = 'rt_'.$statusCount->rt;
            $status = str_replace(' ', '_', strtolower(StatusKKPJEnum::label($statusCount->kk_pj))); // Mengubah spasi menjadi underscore
            $total = $statusCount->total;

            if (! isset($result[$rt])) {
                $result[$rt] = [];
            }

            $result[$rt][$status] = $total;
            $totals[$statusCount->kk_pj] += $total;
        }

        foreach ($totals as $key => $value) {
            $totals[str_replace(' ', '_', strtolower(StatusKKPJEnum::label($key)))] = $value;
            unset($totals[$key]);
        }

        $result['total_keseluruhan'] = $totals;

        return $result;
    }

    /**
     * @OA\Get(
     *      path="/statistik-status-sosial-warga",
     *      tags={"Statistic Warga"},
     *      summary="Statistic Jumlah Jenis Status Sosial Warga",
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function hitungStatusSosialPerRT()
    {
        $statusCounts = Warga::select('rt', 'status_sosial', DB::raw('count(*) as total'))
            ->groupBy('rt', 'status_sosial')
            ->orderBy('rt')
            ->get();

        $result = [];
        $totals = [
            StatusSosialEnum::MENENGAH_KE_ATAS => 0,
            StatusSosialEnum::MENENGAH_KE_BAWAH => 0,
            StatusSosialEnum::KURANG_MAMPU => 0,
        ];

        foreach ($statusCounts as $statusCount) {
            $rt = 'rt_'.$statusCount->rt;
            $status = str_replace(' ', '_', strtolower(StatusSosialEnum::label($statusCount->status_sosial)));
            $total = $statusCount->total;

            if (! isset($result[$rt])) {
                $result[$rt] = [];
            }

            $result[$rt][$status] = $total;
            $totals[$statusCount->status_sosial] += $total;
        }

        foreach ($totals as $key => $value) {
            $totals[str_replace(' ', '_', strtolower(StatusSosialEnum::label($key)))] = $value;
            unset($totals[$key]);
        }

        $result['total_keseluruhan'] = $totals;

        return $result;
    }

    /**
     * @OA\Get(
     *      path="/statistik-status-warga",
     *      tags={"Statistic Warga"},
     *      summary="Statistic Jumlah Jenis Status Warga",
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function hitungStatusWargaPerRT()
    {
        $statusCounts = Warga::select('rt', 'status_warga', DB::raw('count(*) as total'))
            ->groupBy('rt', 'status_warga')
            ->orderBy('rt')
            ->get();

        $result = [];
        $totals = [
            StatusWargaEnum::WARGA_RW_12 => 0,
            StatusWargaEnum::WARGA_LUAR => 0,
        ];

        foreach ($statusCounts as $statusCount) {
            $rt = 'rt_'.$statusCount->rt;
            $status = str_replace(' ', '_', strtolower(StatusWargaEnum::label($statusCount->status_warga))); // Mengubah spasi menjadi underscore
            $total = $statusCount->total;

            if (! isset($result[$rt])) {
                $result[$rt] = [];
            }

            $result[$rt][$status] = $total;
            $totals[$statusCount->status_warga] += $total;
        }

        foreach ($totals as $key => $value) {
            $totals[str_replace(' ', '_', strtolower(StatusWargaEnum::label($key)))] = $value;
            unset($totals[$key]);
        }

        $result['total_keseluruhan'] = $totals;

        return $result;
    }

     /**
     * @OA\Get(
     *      path="/statistik-status-pekerjaan-warga",
     *      tags={"Statistic Warga"},
     *      summary="Statistic Jumlah Jenis Status Pekerjaan Warga",
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function hitungStatusPekerjaanPerRT()
    {
        $statusCounts = Warga::select('rt', 'status_pekerjaan', DB::raw('count(*) as total'))
            ->groupBy('rt', 'status_pekerjaan')
            ->orderBy('rt')
            ->get();

        $result = [];
        $totals = [];

        foreach ($statusCounts as $statusCount) {
            $rt = 'rt_'.$statusCount->rt;
            $status_label = StatusPekerjaanEnum::label($statusCount->status_pekerjaan);
            $status = str_replace(['(', ')', '/'], '', str_replace(' ', '_', strtolower($status_label)));
            $total = $statusCount->total;

            if (! isset($result[$rt])) {
                $result[$rt] = [];
            }

            $result[$rt][$status] = $total;

            if (! isset($totals[$status])) {
                $totals[$status] = $total;
            } else {
                $totals[$status] += $total;
            }
        }

        $total_keseluruhan = [];
        foreach ($totals as $key => $value) {
            $key_formatted = str_replace(' ', '_', $key);
            $total_keseluruhan[$key_formatted] = $value;
        }

        $result['total_keseluruhan'] = $total_keseluruhan;

        return $result;
    }
}
