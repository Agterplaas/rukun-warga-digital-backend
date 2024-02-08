<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Http\Requests\StoreWargaRequest;
use App\Http\Resources\WargaResource;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\DB;

class WargaController extends Controller
{
    /**
     * @OA\Get(
     *      path="/wargas",
     *      tags={"Warga"},
     *      summary="List of Warga",
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

        $wargas = QueryBuilder::for(Warga::class)
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

        return WargaResource::collection($wargas);
    }

    /**
     * @OA\Post(
     *      path="/wargas",
     *      tags={"Warga"},
     *      summary="Store Warga",
     *      @OA\RequestBody(
     *         description="Body",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="nik", ref="#/components/schemas/Warga/properties/nik"),
*              @OA\Property(property="alamat_ktp", ref="#/components/schemas/Warga/properties/alamat_ktp"),
*              @OA\Property(property="blok", ref="#/components/schemas/Warga/properties/blok"),
*              @OA\Property(property="nomor", ref="#/components/schemas/Warga/properties/nomor"),
*              @OA\Property(property="rt", ref="#/components/schemas/Warga/properties/rt"),

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
        $warga = Warga::create($request->all());

        return $this->sendSuccess(new WargaResource($warga), 'Data berhasil disimpan.', 201);
    }

    /**
     * @OA\Get(
     *      path="/wargas/{id}",
     *      tags={"Warga"},
     *      summary="Warga details",
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="Warga ID"),
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
     * @OA\Put(
     *      path="/wargas/{id}",
     *      tags={"Warga"},
     *      summary="Update Warga",
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="Warga ID"),
     *      @OA\RequestBody(
     *         description="Body",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="nik", ref="#/components/schemas/Warga/properties/nik"),
*              @OA\Property(property="alamat_ktp", ref="#/components/schemas/Warga/properties/alamat_ktp"),
*              @OA\Property(property="blok", ref="#/components/schemas/Warga/properties/blok"),
*              @OA\Property(property="nomor", ref="#/components/schemas/Warga/properties/nomor"),
*              @OA\Property(property="rt", ref="#/components/schemas/Warga/properties/rt"),

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
        $warga->update($request->all());

        return $this->sendSuccess(new WargaResource($warga), 'Data sukses disimpan.');
    }

    /**
     * @OA\Delete(
     *      path="/wargas/{id}",
     *      tags={"Warga"},
     *      summary="Warga Removal",
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="Warga ID"),
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

    /**
     * @OA\Get(
     *      path="/wargas/schema",
     *      tags={"Warga"},
     *      summary="Schema of Warga",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function schema(Request $request)
    {
        $fields = DB::select('describe warga');
        $schema = [
            'name' => 'warga',
            'module' => 'Warga',
            'primary_key' => 'id',
            'endpoint' => '/wargas',
            'scheme' => array_values($fields),
        ];

        return $this->sendSuccess($schema, 'Data berhasil ditampilkan.');
    }
}
