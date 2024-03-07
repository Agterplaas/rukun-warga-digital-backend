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

    public function store(StoreMJabatanRequest $request)
    {
        $mJabatan = Jabatan::create($request->all());

        return $this->sendSuccess(new MJabatanResource($mJabatan), 'Data berhasil disimpan.', 201);
    }

    public function show(Jabatan $mJabatan)
    {
        return $this->sendSuccess(new MJabatanResource($mJabatan), 'Data berhasil ditampilkan.');
    }

    public function update(StoreMJabatanRequest $request, Jabatan $mJabatan)
    {
        $mJabatan->update($request->all());

        return $this->sendSuccess(new MJabatanResource($mJabatan), 'Data sukses disimpan.');
    }

    public function destroy(Jabatan $mJabatan)
    {
        $mJabatan->delete();

        return $this->sendSuccess([], null, 204);
    }

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
