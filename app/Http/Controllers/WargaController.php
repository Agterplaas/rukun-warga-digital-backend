<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportedWargaRequest;
use App\Http\Requests\StoreWargaRequest;
use App\Http\Resources\WargaResource;
use App\Imports\ExcelToArrayImport;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $rows = 100;
        if ($request->filled('rows')) {
            $rows = $request->rows;
        }

        $perPage = $request->query('per_page', $rows);

        $warga = QueryBuilder::for(Warga::class)
            ->allowedFilters([
                AllowedFilter::callback(
                    'keyword',
                    fn (Builder $query, $value) => $query->where('nama', 'like', '%'.$value.'%')
                ),
                AllowedFilter::exact('id'),
                'nama',
            ])
            ->allowedSorts('nama', 'jenis_kelamin', 'tgl_lahir', 'alamat_ktp', 'blok', 'nomor', 'rt', 'agama', 'pekerjaan', 'no_telp', 'status_warga', 'status_kawin', 'status_sosial', 'catatan', 'kk_pj')
            ->paginate($perPage)
            ->appends($request->query());

        return WargaResource::collection($warga);
    }

    public function store(StoreWargaRequest $request)
    {
        $requestData = $request->all();
        $requestData['no_kk'] = encrypt($request->input('no_kk'));
        $requestData['nik'] = encrypt($request->input('nik'));

        $warga = Warga::create($requestData);

        return $this->sendSuccess(new WargaResource($warga), 'Data berhasil disimpan.', 201);
    }

    public function documentExcelImport(ImportedWargaRequest $request)
    {
        $import = new ExcelToArrayImport();
        Excel::import($import, $request->file('file_template'));
        $item = collect($import->getArrayData())->first();

        $storeRequest = new StoreWargaRequest([
            'data' => collect($item)
                ->map(
                    function ($item) {
                        return [
                            'no_kk' => encrypt($item['no_kk']),
                            'nik' => encrypt($item['nik']),
                            'nama' => $item['nama_lengkap'],
                            'jenis_kelamin' => $item['jenis_kelamin'],
                            'tgl_lahir' => $item['tanggal_lahir'],
                            'alamat_ktp' => $item['alamat_ktp'],
                            'blok' => $item['blok'],
                            'nomor' => $item['nomor'],
                            'rt' => $item['rt'],
                            'agama' => $item['agama'],
                            'no_telp' => $item['nomor_telepon'],
                            'status_pekerjaan' => $item['status_pekerjaan'],
                            'pekerjaan' => $item['pekerjaan'],
                            'status_warga' => $item['status_warga'],
                            'status_kawin' => $item['status_kawin'],
                            'status_sosial' => $item['status_sosial'],
                            'kk_pj' => $item['status_anggota_rumah'],
                            'catatan' => $item['catatan'],
                        ];
                    }
                )
                ->toArray(),
        ]);

        return $this->storeImport($storeRequest);
    }

    public function storeImport(StoreWargaRequest $request)
    {
        $requestData = $request->all()['data'];

        foreach ($requestData as $item) {
            $tgl_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item['tgl_lahir']);
            $item['tgl_lahir'] = $tgl_lahir->format('Y-m-d');

            if ($item['jenis_kelamin'] == 'LAKI LAKI') {
                $item['jenis_kelamin'] = 0;
            } elseif ($item['jenis_kelamin'] == 'PEREMPUAN') {
                $item['jenis_kelamin'] = 1;
            }

            switch ($item['agama']) {
                case 'ISLAM':
                    $item['agama'] = 0;
                    break;
                case 'KRISTEN KATOLIK':
                    $item['agama'] = 1;
                    break;
                case 'KRISTEN PROTESTAN':
                    $item['agama'] = 2;
                    break;
                case 'HINDU':
                    $item['agama'] = 3;
                    break;
                case 'BUDDHA':
                    $item['agama'] = 4;
                    break;
                default:
                    break;
            }

            switch ($item['status_kawin']) {
                case 'BELUM KAWIN':
                    $item['status_kawin'] = 0;
                    break;
                case 'KAWIN':
                    $item['status_kawin'] = 1;
                    break;
                case 'CERAI':
                    $item['status_kawin'] = 2;
                    break;
                case 'JANDA':
                    $item['status_kawin'] = 3;
                    break;
                case 'DUDA':
                    $item['status_kawin'] = 4;
                    break;
                default:
                    break;
            }

            switch ($item['status_warga']) {
                case 'WARGA RW 12':
                    $item['status_warga'] = 0;
                    break;
                case 'WARGA LUAR':
                    $item['status_warga'] = 1;
                    break;
                default:
                    break;
            }

            switch ($item['status_sosial']) {
                case 'MENENGAH KE ATAS':
                    $item['status_sosial'] = 0;
                    break;
                case 'MENENGAH KE BAWAH':
                    $item['status_sosial'] = 1;
                    break;
                case 'KURANG MAMPU':
                    $item['status_sosial'] = 2;
                    break;
                default:
                    break;
            }

            switch ($item['kk_pj']) {
                case 'ANGGOTA':
                    $item['kk_pj'] = 0;
                    break;
                case 'KEPALA RUMAH TANGGA':
                    $item['kk_pj'] = 1;
                    break;
                case 'PENANGGUNG JAWAB':
                    $item['kk_pj'] = 2;
                    break;
                default:
                    break;
            }

            switch ($item['status_pekerjaan']) {
                case 'BEKERJA':
                    $item['status_pekerjaan'] = 0;
                    break;
                case 'MAHASISWA/I':
                    $item['status_pekerjaan'] = 1;
                    break;
                case 'PENGANGGURAN':
                    $item['status_pekerjaan'] = 2;
                    break;
                case 'PENGANGGURAN BERPENDIDIKAN TINGGI':
                    $item['status_pekerjaan'] = 3;
                    break;
                case 'PENSIUNAN':
                    $item['status_pekerjaan'] = 4;
                    break;
                case 'PELAJAR':
                    $item['status_pekerjaan'] = 5;
                    break;
                case 'TIDAK BEKERJA (TIDAK MENCARI PEKERJAAN)':
                    $item['status_pekerjaan'] = 6;
                    break;
                case 'BERKEBUTUHAN KHUSUS':
                    $item['status_pekerjaan'] = 7;
                    break;
                default:
                    break;
            }

            Warga::create($item);
        }

        return $this->sendSuccess(null, 'Data berhasil disimpan.', 201);
    }

    public function show(Warga $warga)
    {
        return $this->sendSuccess(new WargaResource($warga), 'Data berhasil ditampilkan.');
    }

    public function showByNoKK($no_kk)
    {
        $encryptedNoKKs = Warga::pluck('no_kk');
        $decryptedNoKKs = $encryptedNoKKs->map(function ($encryptedNoKK) {
            return decrypt($encryptedNoKK);
        });

        $matchingWarga = [];
        foreach ($decryptedNoKKs as $index => $value) {
            if ($value == $no_kk) {
                $matchingWarga[] = Warga::where('no_kk', $encryptedNoKKs[$index])->first();
            }
        }

        return $this->sendSuccess(WargaResource::collection(collect($matchingWarga)), 'Data berhasil ditampilkan.');
    }

    public function update(StoreWargaRequest $request, Warga $warga)
    {
        $requestData = $request->all();
        $requestData['no_kk'] = encrypt($request->input('no_kk'));
        $requestData['nik'] = encrypt($request->input('nik'));

        $warga->update($requestData);

        return $this->sendSuccess(new WargaResource($warga), 'Data berhasil diperbarui.');
    }

    public function destroy(Warga $warga)
    {
        $warga->delete();

        return $this->sendSuccess([], null, 204);
    }
}
