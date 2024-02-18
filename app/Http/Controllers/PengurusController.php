<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengurusRequest;
use App\Http\Resources\PengurusResource;
use App\Models\Pengurus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PengurusController extends Controller
{
    /**
     * @OA\Get(
     *      path="/penguruses",
     *      tags={"Pengurus"},
     *      summary="List of Pengurus",
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
        $rows = 10;
        if ($request->filled('rows')) {
            $rows = $request->rows;
        }

        $perPage = $request->query('per_page', $rows);

        $penguruses = QueryBuilder::for(Pengurus::class)
            ->with(['warga', 'jabatan'])
            ->allowedFilters([
                AllowedFilter::callback(
                    'keyword',
                    fn (Builder $query, $value) => $query->where('name', 'like', '%'.$value.'%')
                ),
                AllowedFilter::exact('id'),
                'name',
            ])
            ->allowedSorts('name', 'created_at')
            ->paginate($perPage)
            ->appends($request->query());

        return PengurusResource::collection($penguruses);
    }

    /**
     * @OA\Post(
     *      path="/penguruses",
     *      tags={"Pengurus"},
     *      summary="Store Pengurus",
     *
     *      @OA\RequestBody(
     *         description="Body",
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="warga_id", ref="#/components/schemas/Pengurus/properties/warga_id"),
     *              @OA\Property(property="jabatan_id", ref="#/components/schemas/Pengurus/properties/jabatan_id"),

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
     *              @OA\Property(property="warga_id", type="array", @OA\Items(example={"warga_id field is required."})),
     *              @OA\Property(property="jabatan_id", type="array", @OA\Items(example={"jabatan_id field is required."})),

     *          ),
     *      ),
     * )
     */
    public function store(StorePengurusRequest $request)
    {
        $request->merge(['jabatan_id' => json_encode($request->jabatan_id)]);
        $pengurus = Pengurus::create($request->all());

        return $this->sendSuccess(new PengurusResource($pengurus), 'Data berhasil disimpan.', 201);
    }

    /**
     * @OA\Get(
     *      path="/penguruses/{id}",
     *      tags={"Pengurus"},
     *      summary="Pengurus details",
     *
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="Pengurus ID"),
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function show(Pengurus $pengurus)
    {
        return $this->sendSuccess(new PengurusResource($pengurus), 'Data berhasil ditampilkan.');
    }

    /**
     * @OA\Put(
     *      path="/penguruses/{id}",
     *      tags={"Pengurus"},
     *      summary="Update Pengurus",
     *
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="Pengurus ID"),
     *
     *      @OA\RequestBody(
     *         description="Body",
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *              @OA\Property(property="warga_id", ref="#/components/schemas/Pengurus/properties/warga_id"),
     *              @OA\Property(property="jabatan_id", ref="#/components/schemas/Pengurus/properties/jabatan_id"),

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
     *              @OA\Property(property="warga_id", type="array", @OA\Items(example={"warga_id field is required."})),
     *              @OA\Property(property="jabatan_id", type="array", @OA\Items(example={"jabatan_id field is required."})),

     *          ),
     *      ),
     * )
     */
    public function update(StorePengurusRequest $request, Pengurus $pengurus)
    {
        $request->merge(['jabatan_id' => json_encode($request->jabatan_id)]);

        $pengurus->warga_id = $request->input('warga_id');
        $pengurus->jabatan_id = $request->input('jabatan_id');
        $pengurus->save();

        return $this->sendSuccess(new PengurusResource($pengurus), 'Data berhasil disimpan.');
    }

    /**
     * @OA\Delete(
     *      path="/penguruses/{id}",
     *      tags={"Pengurus"},
     *      summary="Pengurus Removal",
     *
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="Pengurus ID"),
     *
     *      @OA\Response(
     *          response=204,
     *          description="Response success no content",
     *      ),
     * )
     */
    public function destroy(Pengurus $pengurus)
    {
        $pengurus->delete();

        return $this->sendSuccess([], null, 204);
    }

    /**
     * @OA\Get(
     *      path="/penguruses/schema",
     *      tags={"Pengurus"},
     *      summary="Schema of Pengurus",
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function schema(Request $request)
    {
        $fields = DB::select('describe pengurus');
        $schema = [
            'name' => 'pengurus',
            'module' => 'Pengurus',
            'primary_key' => 'id',
            'endpoint' => '/penguruses',
            'scheme' => array_values($fields),
        ];

        return $this->sendSuccess($schema, 'Data berhasil ditampilkan.');
    }
}
