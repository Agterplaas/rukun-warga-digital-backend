<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\BarangPinjam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\BarangPinjamResource;
use App\Http\Requests\StoreBarangPinjamRequest;

class BarangPinjamController extends Controller
{
    /**
     * @OA\Get(
     *      path="/barang-pinjam",
     *      tags={"BarangPinjam"},
     *      summary="List of BarangPinjam",
     *      @OA\Parameter(in="query", required=false, name="filter[name]", @OA\Schema(type="string"), example="keyword"),
     *      @OA\Parameter(in="query", required=false, name="filter[keyword]", @OA\Schema(type="string"), example="keyword"),
     *      @OA\Parameter(in="query", required=false, name="sort", @OA\Schema(type="string"), example="name"),
     *      @OA\Parameter(in="query", required=false, name="page", @OA\Schema(type="string"), example="1"),
     *      @OA\Parameter(in="query", required=false, name="rows", @OA\Schema(type="string"), example="10"),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function index(Request $request)
    {
        $rows = 10;
        if ($request->filled('rows')) {
            $rows = $request->rows;
        }

        $perPage = $request->query('per_page', $rows);

        $barangPinjams = QueryBuilder::for(BarangPinjam::class)
            ->allowedFilters([
                AllowedFilter::callback(
                    'keyword',
                    fn (Builder $query, $value) => $query->where('name', 'like', '%' . $value . '%')
                ),
                AllowedFilter::exact('id'),
                'name',
            ])
            ->allowedSorts('name', 'created_at')
            ->paginate($perPage)
            ->appends($request->query());

        return BarangPinjamResource::collection($barangPinjams);
    }

    public function store(StoreBarangPinjamRequest $request)
    {
        try {
            $data = $request->all();
            $barangMasuk = BarangMasuk::where('jenis_barang', $data['jenis_barang'])->first();

            if (!$barangMasuk || $barangMasuk->jml_barang < $data['jml_barang']) {
                return $this->sendError('Jumlah barang yang tersedia tidak mencukupi.', 400);
            }

            $barangPinjam = BarangPinjam::create($data);
            $barangMasuk->decrement('jml_barang', $data['jml_barang']);

            return $this->sendSuccess(new BarangPinjamResource($barangPinjam), 'Data berhasil disimpan.', 201);
        } catch (\Exception $e) {
            return $this->sendError('Gagal menyimpan data: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/barang-pinjam/{id}",
     *      tags={"BarangPinjam"},
     *      summary="BarangPinjam details",
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="BarangPinjam ID"),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function show(BarangPinjam $barangPinjam)
    {
        return $this->sendSuccess(new BarangPinjamResource($barangPinjam), 'Data berhasil ditampilkan.');
    }

    /**
     * @OA\Put(
     *      path="/barang-pinjam/{id}",
     *      tags={"BarangPinjam"},
     *      summary="Update BarangPinjam",
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="BarangPinjam ID"),
     *      @OA\RequestBody(
     *         description="Body",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="acara_id", ref="#/components/schemas/BarangPinjam/properties/acara_id"),
*              @OA\Property(property="jenis_barang", ref="#/components/schemas/BarangPinjam/properties/jenis_barang"),
*              @OA\Property(property="jml_barang", ref="#/components/schemas/BarangPinjam/properties/jml_barang"),
*              @OA\Property(property="catatan", ref="#/components/schemas/BarangPinjam/properties/catatan"),
*              @OA\Property(property="storage", ref="#/components/schemas/BarangPinjam/properties/storage"),
*              @OA\Property(property="kepemilikan", ref="#/components/schemas/BarangPinjam/properties/kepemilikan"),

     *         ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="true"),
     *              @OA\Property(property="message", type="string", example="Data sukses disimpan."),
     *          )
     *      ),
     *      @OA\Response(
     *          response="422",
     *          description="error",
     *          @OA\JsonContent(
    *              @OA\Property(property="acara_id", type="array", @OA\Items(example={"acara_id field is required."})),
*              @OA\Property(property="jenis_barang", type="array", @OA\Items(example={"jenis_barang field is required."})),
*              @OA\Property(property="jml_barang", type="array", @OA\Items(example={"jml_barang field is required."})),
*              @OA\Property(property="catatan", type="array", @OA\Items(example={"catatan field is required."})),
*              @OA\Property(property="storage", type="array", @OA\Items(example={"storage field is required."})),
*              @OA\Property(property="kepemilikan", type="array", @OA\Items(example={"kepemilikan field is required."})),

     *          ),
     *      ),
     * )
     */
    public function update(StoreBarangPinjamRequest $request, BarangPinjam $barangPinjam)
    {
        $barangPinjam->update($request->all());

        return $this->sendSuccess(new BarangPinjamResource($barangPinjam), 'Data sukses disimpan.');
    }

    /**
     * @OA\Delete(
     *      path="/barang-pinjam/{id}",
     *      tags={"BarangPinjam"},
     *      summary="BarangPinjam Removal",
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="BarangPinjam ID"),
     *      @OA\Response(
     *          response=204,
     *          description="Response success no content",
     *      ),
     * )
     */
    public function destroy(BarangPinjam $barangPinjam)
    {
        $barangPinjam->delete();

        return $this->sendSuccess([], null, 204);
    }

    /**
     * @OA\Get(
     *      path="/barang-pinjam/schema",
     *      tags={"BarangPinjam"},
     *      summary="Schema of BarangPinjam",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function schema(Request $request)
    {
        $fields = DB::select('describe barang_pinjam');
        $schema = [
            'name' => 'barang_pinjam',
            'module' => 'BarangPinjam',
            'primary_key' => 'id',
            'endpoint' => '/barang-pinjam',
            'scheme' => array_values($fields),
        ];

        return $this->sendSuccess($schema, 'Data berhasil ditampilkan.');
    }
}
