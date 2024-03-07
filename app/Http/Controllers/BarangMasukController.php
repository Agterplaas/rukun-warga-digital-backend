<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Http\Requests\StoreBarangMasukRequest;
use App\Http\Resources\BarangMasukResource;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    /**
     * @OA\Get(
     *      path="/barang-masuks",
     *      tags={"BarangMasuk"},
     *      summary="List of BarangMasuk",
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

        $barangMasuks = QueryBuilder::for(BarangMasuk::class)
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

        return BarangMasukResource::collection($barangMasuks);
    }

    /**
     * @OA\Post(
     *      path="/barang-masuks",
     *      tags={"BarangMasuk"},
     *      summary="Store BarangMasuk",
     *      @OA\RequestBody(
     *         description="Body",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="role_id", ref="#/components/schemas/BarangMasuk/properties/role_id"),
*              @OA\Property(property="jenis_barang", ref="#/components/schemas/BarangMasuk/properties/jenis_barang"),
*              @OA\Property(property="jml_barang", ref="#/components/schemas/BarangMasuk/properties/jml_barang"),
*              @OA\Property(property="catatan", ref="#/components/schemas/BarangMasuk/properties/catatan"),
*              @OA\Property(property="storage", ref="#/components/schemas/BarangMasuk/properties/storage"),
*              @OA\Property(property="kepemilikan", ref="#/components/schemas/BarangMasuk/properties/kepemilikan"),

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
    *              @OA\Property(property="role_id", type="array", @OA\Items(example={"role_id field is required."})),
*              @OA\Property(property="jenis_barang", type="array", @OA\Items(example={"jenis_barang field is required."})),
*              @OA\Property(property="jml_barang", type="array", @OA\Items(example={"jml_barang field is required."})),
*              @OA\Property(property="catatan", type="array", @OA\Items(example={"catatan field is required."})),
*              @OA\Property(property="storage", type="array", @OA\Items(example={"storage field is required."})),
*              @OA\Property(property="kepemilikan", type="array", @OA\Items(example={"kepemilikan field is required."})),

     *          ),
     *      ),
     * )
     */
    public function store(StoreBarangMasukRequest $request)
    {
        $data = $request->all();

       
        $barangMasuk = BarangMasuk::updateOrCreate(
            ['jenis_barang' => $data['jenis_barang']],
            ['jml_barang' => \DB::raw('jml_barang + ' . $data['jml_barang']),
             'role_id' => $data['role_id'],
             'catatan' => $data['catatan'],
             'storage' => $data['storage'],
             'kepemilikan' => $data['kepemilikan'],
            ]
        );
        // $data['jenis_barang'] === $barangMasuk->jenis_barang;

        return $this->sendSuccess(new BarangMasukResource($barangMasuk), 'Data berhasil disimpan.', 201);
    }

    /**
     * @OA\Get(
     *      path="/barang-masuks/{id}",
     *      tags={"BarangMasuk"},
     *      summary="BarangMasuk details",
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="BarangMasuk ID"),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function show(BarangMasuk $barangMasuk)
    {
        return $this->sendSuccess(new BarangMasukResource($barangMasuk), 'Data berhasil ditampilkan.');
    }

    /**
     * @OA\Put(
     *      path="/barang-masuks/{id}",
     *      tags={"BarangMasuk"},
     *      summary="Update BarangMasuk",
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="BarangMasuk ID"),
     *      @OA\RequestBody(
     *         description="Body",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="role_id", ref="#/components/schemas/BarangMasuk/properties/role_id"),
*              @OA\Property(property="jenis_barang", ref="#/components/schemas/BarangMasuk/properties/jenis_barang"),
*              @OA\Property(property="jml_barang", ref="#/components/schemas/BarangMasuk/properties/jml_barang"),
*              @OA\Property(property="catatan", ref="#/components/schemas/BarangMasuk/properties/catatan"),
*              @OA\Property(property="storage", ref="#/components/schemas/BarangMasuk/properties/storage"),
*              @OA\Property(property="kepemilikan", ref="#/components/schemas/BarangMasuk/properties/kepemilikan"),

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
    *              @OA\Property(property="role_id", type="array", @OA\Items(example={"role_id field is required."})),
*              @OA\Property(property="jenis_barang", type="array", @OA\Items(example={"jenis_barang field is required."})),
*              @OA\Property(property="jml_barang", type="array", @OA\Items(example={"jml_barang field is required."})),
*              @OA\Property(property="catatan", type="array", @OA\Items(example={"catatan field is required."})),
*              @OA\Property(property="storage", type="array", @OA\Items(example={"storage field is required."})),
*              @OA\Property(property="kepemilikan", type="array", @OA\Items(example={"kepemilikan field is required."})),

     *          ),
     *      ),
     * )
     */
    public function update(StoreBarangMasukRequest $request, BarangMasuk $barangMasuk)
    {
        $barangMasuk->update($request->all());

        return $this->sendSuccess(new BarangMasukResource($barangMasuk), 'Data sukses disimpan.');
    }

    /**
     * @OA\Delete(
     *      path="/barang-masuks/{id}",
     *      tags={"BarangMasuk"},
     *      summary="BarangMasuk Removal",
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="BarangMasuk ID"),
     *      @OA\Response(
     *          response=204,
     *          description="Response success no content",
     *      ),
     * )
     */
    public function destroy(BarangMasuk $barangMasuk)
    {
        $barangMasuk->delete();

        return $this->sendSuccess([], null, 204);
    }

    /**
     * @OA\Get(
     *      path="/barang-masuks/schema",
     *      tags={"BarangMasuk"},
     *      summary="Schema of BarangMasuk",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function schema(Request $request)
    {
        $fields = DB::select('describe barang_masuk');
        $schema = [
            'name' => 'barang_masuk',
            'module' => 'BarangMasuk',
            'primary_key' => 'id',
            'endpoint' => '/barang-masuks',
            'scheme' => array_values($fields),
        ];

        return $this->sendSuccess($schema, 'Data berhasil ditampilkan.');
    }
}
