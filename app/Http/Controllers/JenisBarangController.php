<?php

namespace App\Http\Controllers;

use App\Models\Master\JenisBarang;
use App\Http\Requests\StoreJenisBarangRequest;
use App\Http\Resources\JenisBarangResource;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\DB;

class JenisBarangController extends Controller
{
    /**
     * @OA\Get(
     *      path="/jenis-barangs",
     *      tags={"JenisBarang"},
     *      summary="List of JenisBarang",
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

        $jenisBarangs = QueryBuilder::for(JenisBarang::class)
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

        return JenisBarangResource::collection($jenisBarangs);
    }

    /**
     * @OA\Post(
     *      path="/jenis-barangs",
     *      tags={"JenisBarang"},
     *      summary="Store JenisBarang",
     *      @OA\RequestBody(
     *         description="Body",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="m_kategori_barang_id", ref="#/components/schemas/JenisBarang/properties/m_kategori_barang_id"),
*              @OA\Property(property="nama", ref="#/components/schemas/JenisBarang/properties/nama"),

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
    *              @OA\Property(property="m_kategori_barang_id", type="array", @OA\Items(example={"m_kategori_barang_id field is required."})),
*              @OA\Property(property="nama", type="array", @OA\Items(example={"nama field is required."})),

     *          ),
     *      ),
     * )
     */
    public function store(StoreJenisBarangRequest $request)
    {
        $jenisBarang = JenisBarang::create($request->all());

        return $this->sendSuccess(new JenisBarangResource($jenisBarang), 'Data berhasil disimpan.', 201);
    }

    /**
     * @OA\Get(
     *      path="/jenis-barangs/{id}",
     *      tags={"JenisBarang"},
     *      summary="JenisBarang details",
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="JenisBarang ID"),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function show(JenisBarang $jenisBarang)
    {
        return $this->sendSuccess(new JenisBarangResource($jenisBarang), 'Data berhasil ditampilkan.');
    }

    /**
     * @OA\Put(
     *      path="/jenis-barangs/{id}",
     *      tags={"JenisBarang"},
     *      summary="Update JenisBarang",
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="JenisBarang ID"),
     *      @OA\RequestBody(
     *         description="Body",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="m_kategori_barang_id", ref="#/components/schemas/JenisBarang/properties/m_kategori_barang_id"),
*              @OA\Property(property="nama", ref="#/components/schemas/JenisBarang/properties/nama"),

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
    *              @OA\Property(property="m_kategori_barang_id", type="array", @OA\Items(example={"m_kategori_barang_id field is required."})),
*              @OA\Property(property="nama", type="array", @OA\Items(example={"nama field is required."})),

     *          ),
     *      ),
     * )
     */
    public function update(StoreJenisBarangRequest $request, JenisBarang $jenisBarang)
    {
        $jenisBarang->update($request->all());

        return $this->sendSuccess(new JenisBarangResource($jenisBarang), 'Data sukses disimpan.');
    }

    /**
     * @OA\Delete(
     *      path="/jenis-barangs/{id}",
     *      tags={"JenisBarang"},
     *      summary="JenisBarang Removal",
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="JenisBarang ID"),
     *      @OA\Response(
     *          response=204,
     *          description="Response success no content",
     *      ),
     * )
     */
    public function destroy(JenisBarang $jenisBarang)
    {
        $jenisBarang->delete();

        return $this->sendSuccess([], null, 204);
    }

    /**
     * @OA\Get(
     *      path="/jenis-barangs/schema",
     *      tags={"JenisBarang"},
     *      summary="Schema of JenisBarang",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function schema(Request $request)
    {
        $fields = DB::select('describe m_jenis_barang');
        $schema = [
            'name' => 'm_jenis_barang',
            'module' => 'JenisBarang',
            'primary_key' => 'id',
            'endpoint' => '/jenis-barangs',
            'scheme' => array_values($fields),
        ];

        return $this->sendSuccess($schema, 'Data berhasil ditampilkan.');
    }
}
