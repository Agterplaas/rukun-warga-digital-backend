<?php

namespace App\Http\Controllers;

use App\Models\Master\KategoriBarang;
use App\Http\Requests\StoreKategoriBarangRequest;
use App\Http\Resources\KategoriBarangResource;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\DB;

class KategoriBarangController extends Controller
{
    /**
     * @OA\Get(
     *      path="/kategori-barangs",
     *      tags={"KategoriBarang"},
     *      summary="List of KategoriBarang",
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

        $kategoriBarangs = QueryBuilder::for(KategoriBarang::class)
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

        return KategoriBarangResource::collection($kategoriBarangs);
    }

    /**
     * @OA\Post(
     *      path="/kategori-barangs",
     *      tags={"KategoriBarang"},
     *      summary="Store KategoriBarang",
     *      @OA\RequestBody(
     *         description="Body",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="nama", ref="#/components/schemas/KategoriBarang/properties/nama"),

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
    *              @OA\Property(property="nama", type="array", @OA\Items(example={"nama field is required."})),

     *          ),
     *      ),
     * )
     */
    public function store(StoreKategoriBarangRequest $request)
    {
        $kategoriBarang = KategoriBarang::create($request->all());

        return $this->sendSuccess(new KategoriBarangResource($kategoriBarang), 'Data berhasil disimpan.', 201);
    }

    /**
     * @OA\Get(
     *      path="/kategori-barangs/{id}",
     *      tags={"KategoriBarang"},
     *      summary="KategoriBarang details",
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="KategoriBarang ID"),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function show(KategoriBarang $kategoriBarang)
    {
        return $this->sendSuccess(new KategoriBarangResource($kategoriBarang), 'Data berhasil ditampilkan.');
    }

    /**
     * @OA\Put(
     *      path="/kategori-barangs/{id}",
     *      tags={"KategoriBarang"},
     *      summary="Update KategoriBarang",
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="KategoriBarang ID"),
     *      @OA\RequestBody(
     *         description="Body",
     *         required=true,
     *         @OA\JsonContent(
     *              @OA\Property(property="nama", ref="#/components/schemas/KategoriBarang/properties/nama"),

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
    *              @OA\Property(property="nama", type="array", @OA\Items(example={"nama field is required."})),

     *          ),
     *      ),
     * )
     */
    public function update(StoreKategoriBarangRequest $request, KategoriBarang $kategoriBarang)
    {
        $kategoriBarang->update($request->all());

        return $this->sendSuccess(new KategoriBarangResource($kategoriBarang), 'Data sukses disimpan.');
    }

    /**
     * @OA\Delete(
     *      path="/kategori-barangs/{id}",
     *      tags={"KategoriBarang"},
     *      summary="KategoriBarang Removal",
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="KategoriBarang ID"),
     *      @OA\Response(
     *          response=204,
     *          description="Response success no content",
     *      ),
     * )
     */
    public function destroy(KategoriBarang $kategoriBarang)
    {
        $kategoriBarang->delete();

        return $this->sendSuccess([], null, 204);
    }

    /**
     * @OA\Get(
     *      path="/kategori-barangs/schema",
     *      tags={"KategoriBarang"},
     *      summary="Schema of KategoriBarang",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function schema(Request $request)
    {
        $fields = DB::select('describe m_kategori_barang');
        $schema = [
            'name' => 'm_kategori_barang',
            'module' => 'KategoriBarang',
            'primary_key' => 'id',
            'endpoint' => '/kategori-barangs',
            'scheme' => array_values($fields),
        ];

        return $this->sendSuccess($schema, 'Data berhasil ditampilkan.');
    }
}
