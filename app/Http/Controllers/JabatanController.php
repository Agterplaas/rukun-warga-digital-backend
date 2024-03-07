<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMJabatanRequest;
use App\Http\Resources\MJabatanResource;
use App\Models\Master\Jabatan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class JabatanController extends Controller
{
    /**
     * @OA\Get(
     *      path="/master/jabatan",
     *      tags={"Master Jabatan"},
     *      summary="List of Master-Jabatan",
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

        $mJabatans = QueryBuilder::for(Jabatan::class)
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

        return MJabatanResource::collection($mJabatans);
    }

    /**
     * @OA\Post(
     *      path="/master/jabatan",
     *      tags={"Master Jabatan"},
     *      summary="Store Master-Jabatan",
     *
     *      @OA\RequestBody(
     *         description="Body",
     *         required=true,
     *
     *         @OA\JsonContent(

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

     *          ),
     *      ),
     * )
     */
    public function store(StoreMJabatanRequest $request)
    {
        $mJabatan = Jabatan::create($request->all());

        return $this->sendSuccess(new MJabatanResource($mJabatan), 'Data berhasil disimpan.', 201);
    }

    /**
     * @OA\Get(
     *      path="/master/jabatan/{id}",
     *      tags={"Master Jabatan"},
     *      summary="Master Jabatan details",
     *
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="Master-Jabatan ID"),
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function show(Jabatan $mJabatan)
    {
        return $this->sendSuccess(new MJabatanResource($mJabatan), 'Data berhasil ditampilkan.');
    }

    /**
     * @OA\Put(
     *      path="/master/jabatan/{id}",
     *      tags={"Master Jabatan"},
     *      summary="Update Master Jabatan",
     *
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="Master-Jabatan ID"),
     *
     *      @OA\RequestBody(
     *         description="Body",
     *         required=true,
     *
     *         @OA\JsonContent(

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

     *          ),
     *      ),
     * )
     */
    public function update(StoreMJabatanRequest $request, Jabatan $mJabatan)
    {
        $mJabatan->update($request->all());

        return $this->sendSuccess(new MJabatanResource($mJabatan), 'Data sukses disimpan.');
    }

    /**
     * @OA\Delete(
     *      path="/master/jabatan/{id}",
     *      tags={"Master Jabatan"},
     *      summary="Master Jabatan Removal",
     *
     *      @OA\Parameter(in="path", required=true, name="id", @OA\Schema(type="integer"), description="Master-Jabatan ID"),
     *
     *      @OA\Response(
     *          response=204,
     *          description="Response success no content",
     *      ),
     * )
     */
    public function destroy(Jabatan $mJabatan)
    {
        $mJabatan->delete();

        return $this->sendSuccess([], null, 204);
    }

    /**
     * @OA\Get(
     *      path="/master/jabatan/schema",
     *      tags={"Master Jabatan"},
     *      summary="Schema of Master-Jabatan",
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     * )
     */
    public function schema(Request $request)
    {
        $fields = DB::select('describe m_jabatan');
        $schema = [
            'name' => 'm_jabatan',
            'module' => 'MJabatan',
            'primary_key' => 'id',
            'endpoint' => '/m-jabatans',
            'scheme' => array_values($fields),
        ];

        return $this->sendSuccess($schema, 'Data berhasil ditampilkan.');
    }
}
