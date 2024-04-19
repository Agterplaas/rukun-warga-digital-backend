<?php

namespace App\Http\Controllers;

use App\Enum\AgamaEnum;
use App\Http\Requests\ImportedWargaRequest;
use App\Http\Requests\StoreWargaRequest;
use App\Http\Resources\WargaResource;
use App\Imports\ExcelToArrayImport;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class WargaController extends Controller
{
    /**
     * @OA\Get(
     *      path="/warga",
     *      tags={"Warga"},
     *      summary="List of Warga",
     *
     *      @OA\Parameter(in="query", required=false, name="filter[name]", @OA\Schema(type="string"), example="keyword"),
     *      @OA\Parameter(in="query", required=false, name="filter[keyword]", @OA\Schema(type="string"), example="keyword"),
     *      @OA\Parameter(in="query", required=false, name="sort", @OA\Schema(type="string"), example="name"),
     *      @OA\Parameter(in="query", required=false, name="page", @OA\Schema(type="string"), example="1"),
     *      @OA\Parameter(in="query", required=false, name="rows", @OA\Schema(type="string"), example="10"),
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
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
                    'nik',
                    function (Builder $query, $value) {
                        // Dekripsi semua data 'nik' di dalam database
                        $encryptedNIKs = Warga::pluck('nik');
                        $decryptedNIKs = $encryptedNIKs->map(function ($encryptedNIK) {
                            return decrypt($encryptedNIK);
                        });
                
                        $matchingWarga = [];
                
                        // Mencari data yang sesuai dengan nilai 'keyword'
                        foreach ($decryptedNIKs as $index => $decryptedNIK) {
                            if (strpos($decryptedNIK, $value) !== false) {
                                // Jika ditemukan, tambahkan data yang sesuai ke dalam array matchingWarga
                                $matchingWarga[] = Warga::where('nik', $encryptedNIKs[$index])->first();
                            }
                        }
                
                        // Ambil ID dari data yang sesuai
                        $matchingIds = collect($matchingWarga)->pluck('id');
                
                        // Gunakan ID-ID yang ditemukan untuk filter
                        $query->whereIn('id', $matchingIds);
                    }
                ),                
                AllowedFilter::callback(
                    'nama',
                    function (Builder $query, $value) {
                        // Dekripsi semua data 'nama' di dalam database
                        $encryptedNames = Warga::pluck('nama');
                        $decryptedNames = $encryptedNames->map(function ($encryptedName) {
                            return decrypt($encryptedName);
                        });
                
                        $matchingWarga = [];
                
                        // Mencari data yang sesuai dengan nilai 'keyword'
                        foreach ($decryptedNames as $index => $decryptedName) {
                            if (strpos($decryptedName, $value) !== false) {
                                // Jika ditemukan, tambahkan data yang sesuai ke dalam array matchingWarga
                                $matchingWarga[] = Warga::where('nama', $encryptedNames[$index])->first();
                            }
                        }
                
                        // Ambil ID dari data yang sesuai
                        $matchingIds = collect($matchingWarga)->pluck('id');
                
                        // Gunakan ID-ID yang ditemukan untuk filter
                        $query->whereIn('id', $matchingIds);
                 }),   
                 AllowedFilter::callback('grup_umur', function ($query, $value, $property) {
                    // Dekripsi semua data 'tgl_lahir' di dalam database
                    $encryptedTglLahir = Warga::pluck('tgl_lahir');
                    $decryptedTglLahir = $encryptedTglLahir->map(function ($encryptedDate) {
                        return decrypt($encryptedDate);
                    });
        
                    $matchingWarga = [];
        
                    // Mencari data yang sesuai dengan grup umur
                    foreach ($decryptedTglLahir as $index => $decryptedDate) {
                        $tgl_lahir = new \DateTime($decryptedDate);
                        $tgl_sekarang = new \DateTime();
                        $selisih = $tgl_lahir->diff($tgl_sekarang);
                        $usia = $selisih->y;
        
                        switch ($value) {
                            case 'balita':
                                if ($usia <= 4) {
                                    $matchingWarga[] = Warga::where('tgl_lahir', $encryptedTglLahir[$index])->first();
                                }
                                break;
                            case 'anak_anak':
                                if ($usia >= 5 && $usia <= 12) {
                                    $matchingWarga[] = Warga::where('tgl_lahir', $encryptedTglLahir[$index])->first();
                                }
                                break;
                            case 'remaja':
                                if ($usia >= 13 && $usia <= 17) {
                                    $matchingWarga[] = Warga::where('tgl_lahir', $encryptedTglLahir[$index])->first();
                                }
                                break;
                            case 'dewasa':
                                if ($usia >= 18 && $usia <= 64) {
                                    $matchingWarga[] = Warga::where('tgl_lahir', $encryptedTglLahir[$index])->first();
                                }
                                break;
                            case 'lansia':
                                if ($usia >= 65) {
                                    $matchingWarga[] = Warga::where('tgl_lahir', $encryptedTglLahir[$index])->first();
                                }
                                break;
                        }
                    }
        
                    // Ambil ID dari data yang sesuai
                    $matchingIds = collect($matchingWarga)->pluck('id');
        
                    // Gunakan ID-ID yang ditemukan untuk filter
                    $query->whereIn('id', $matchingIds);
                }),
                AllowedFilter::exact('id'),
                'alamat_ktp',
                'pekerjaan',
                'rt',
                'blok',
                'nomor',
                'agama',
                'status_kawin',
                'status_sosial',
                'status_warga',
                'status_pekerjaan'
            ])
            ->allowedSorts('nama', 'jenis_kelamin', 'tgl_lahir', 'alamat_ktp', 'blok', 'nomor', 'rt', 'agama', 'pekerjaan', 'no_telp', 'agama', 'status_kawin', 'status_sosial', 'catatan', 'kk_pj')
            ->paginate($perPage)
            ->appends($request->query());
    
        return WargaResource::collection($warga);
    }
    

    /**
     * @OA\Post(
     *      path="/warga",
     *      tags={"Warga"},
     *      summary="Store Warga",
     *
     *      @OA\RequestBody(
     *         description="Body",
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="nik", ref="#/components/schemas/Warga/properties/nik"),
     *              @OA\Property(property="alamat_ktp", ref="#/components/schemas/Warga/properties/alamat_ktp"),
     *              @OA\Property(property="blok", ref="#/components/schemas/Warga/properties/blok"),
     *              @OA\Property(property="nomor", ref="#/components/schemas/Warga/properties/nomor"),
     *              @OA\Property(property="rt", ref="#/components/schemas/Warga/properties/rt"),

     *         ),
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="success", type="boolean", example="true"),
     *              @OA\Property(property="message", type="string", example="Data sukses disimpan."),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response="422",
     *          description="error",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="nik", type="array", @OA\Items(example={"nik field is required."})),
     *              @OA\Property(property="alamat_ktp", type="array", @OA\Items(example={"alamat_ktp field is required."})),
     *              @OA\Property(property="blok", type="array", @OA\Items(example={"blok field is required."})),
     *              @OA\Property(property="nomor", type="array", @OA\Items(example={"nomor field is required."})),
     *              @OA\Property(property="rt", type="array", @OA\Items(example={"rt field is required."})),

     *          ),
     *      ),
     * )
     */
    public function store(StoreWargaRequest $request)
    {
        $requestData = $request->all();
        $requestData['nama'] = encrypt($request->input('nama'));
        $requestData['no_kk'] = encrypt($request->input('no_kk'));
        $requestData['nik'] = encrypt($request->input('nik'));
        $requestData['tgl_lahir'] = encrypt($request->input('tgl_lahir'));

        $warga = Warga::create($requestData);

        return $this->sendSuccess(new WargaResource($warga), 'Data berhasil disimpan.', 201);
    }

    /**
     * @OA\Post(
     *      path="/warga/import",
     *      tags={"Warga"},
     *      summary="Import data from Excel to SQL table",
     *      security={{"bearerAuth":{}}},
     *
     *      @OA\Parameter(
     *          in="path",
     *          name="audit",
     *          required=true,
     *
     *          @OA\Schema(type="integer"),
     *          description="Audit ID"
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *          description="Excel file for data import",
     *
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *
     *              @OA\Schema(
     *                  type="object",
     *
     *                  @OA\Property(
     *                      property="file_template",
     *                      type="file",
     *                      format="binary",
     *                      description="Excel file for data import"
     *                  ),
     *                  @OA\Property(
     *                      property="is_imported",
     *                      type="boolean",
     *                      description="Set to TRUE to indicate that the data is imported"
     *                  ),
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="OK. The data import and storage were successful.",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized. The 'is_imported' parameter must be set to TRUE for using this resource.",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity. Invalid input or data could not be processed.",
     *      ),
     * )
     */
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
                            'nama' => encrypt($item['nama_lengkap']),
                            'tgl_lahir' => encrypt($item['tgl_lahir']),
                            'tempat_lahir' => $item['tempat_lahir'],
                            'jenis_kelamin' => $item['jenis_kelamin'],
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
                case 'KONGHUCU':
                    $item['agama'] = 5;
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

    /**
     * @OA\Get(
     *      path="/warga/{warga}",
     *      tags={"Warga"},
     *      summary="Warga details",
     *
     *      @OA\Parameter(in="path", required=true, name="warga", @OA\Schema(type="integer"), description="Warga ID"),
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function show(Warga $warga)
    {
        return $this->sendSuccess(new WargaResource($warga), 'Data berhasil ditampilkan.');
    }

    /**
     * @OA\Get(
     *      path="/warga/{no_kk}/anggota",
     *      tags={"Warga"},
     *      summary="Anggota warga details",
     *
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="Warga ID"),
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
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

    /**
     * @OA\Put(
     *      path="/warga/{warga}",
     *      tags={"Warga"},
     *      summary="Update Warga",
     *
     *      @OA\Parameter(in="path", required=true, name="warga", @OA\Schema(type="integer"), description="Warga ID"),
     *
     *      @OA\RequestBody(
     *         description="Body",
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="nik", ref="#/components/schemas/Warga/properties/nik"),
     *              @OA\Property(property="alamat_ktp", ref="#/components/schemas/Warga/properties/alamat_ktp"),
     *              @OA\Property(property="blok", ref="#/components/schemas/Warga/properties/blok"),
     *              @OA\Property(property="nomor", ref="#/components/schemas/Warga/properties/nomor"),
     *              @OA\Property(property="rt", ref="#/components/schemas/Warga/properties/rt"),

     *         ),
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="success", type="boolean", example="true"),
     *              @OA\Property(property="message", type="string", example="Data sukses disimpan."),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response="422",
     *          description="error",
     *
     *          @OA\JsonContent(
     *
     *              @OA\Property(property="nik", type="array", @OA\Items(example={"nik field is required."})),
     *              @OA\Property(property="alamat_ktp", type="array", @OA\Items(example={"alamat_ktp field is required."})),
     *              @OA\Property(property="blok", type="array", @OA\Items(example={"blok field is required."})),
     *              @OA\Property(property="nomor", type="array", @OA\Items(example={"nomor field is required."})),
     *              @OA\Property(property="rt", type="array", @OA\Items(example={"rt field is required."})),

     *          ),
     *      ),
     * )
     */
    public function update(StoreWargaRequest $request, Warga $warga)
    {
        $requestData = $request->all();
        $requestData['nama'] = encrypt($request->input('nama'));
        $requestData['no_kk'] = encrypt($request->input('no_kk'));
        $requestData['nik'] = encrypt($request->input('nik'));
        $requestData['tgl_lahir'] = encrypt($request->input('tgl_lahir'));

        $warga->update($requestData);

        return $this->sendSuccess(new WargaResource($warga), 'Data berhasil diperbarui.');
    }

    /**
     * @OA\Delete(
     *      path="/warga/{warga}",
     *      tags={"Warga"},
     *      summary="Warga Removal",
     *
     *      @OA\Parameter(in="path", required=true, name="warga", @OA\Schema(type="integer"), description="Warga ID"),
     *
     *      @OA\Response(
     *          response=204,
     *          description="Response success no content",
     *      ),
     * )
     */
    public function destroy(Warga $warga)
    {
        $warga->delete();

        return $this->sendSuccess([], null, 204);
    }
}
