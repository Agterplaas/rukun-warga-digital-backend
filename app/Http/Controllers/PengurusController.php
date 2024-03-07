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
    public function index(Request $request)
    {
        $rows = 10;
        if ($request->filled('rows')) {
            $rows = $request->rows;
        }

        $perPage = $request->query('per_page', $rows);

        $pengurus = QueryBuilder::for(Pengurus::class)
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

        return PengurusResource::collection($pengurus);
    }

    public function store(StorePengurusRequest $request)
    {
        $request->merge(['jabatan_id' => json_encode($request->jabatan_id)]);
        $pengurus = Pengurus::create($request->all());

        return $this->sendSuccess(new PengurusResource($pengurus), 'Data berhasil disimpan.', 201);
    }

    public function show(Pengurus $pengurus)
    {
        return $this->sendSuccess(new PengurusResource($pengurus), 'Data berhasil ditampilkan.');
    }

    public function update(StorePengurusRequest $request, Pengurus $pengurus)
    {
        $request->merge(['jabatan_id' => json_encode($request->jabatan_id)]);

        $pengurus->warga_id = $request->input('warga_id');
        $pengurus->jabatan_id = $request->input('jabatan_id');
        $pengurus->save();

        return $this->sendSuccess(new PengurusResource($pengurus), 'Data berhasil disimpan.');
    }

    public function destroy(Pengurus $pengurus)
    {
        $pengurus->delete();

        return $this->sendSuccess([], null, 204);
    }

    public function schema(Request $request)
    {
        $fields = DB::select('describe pengurus');
        $schema = [
            'name' => 'pengurus',
            'module' => 'Pengurus',
            'primary_key' => 'id',
            'endpoint' => '/pengurus',
            'scheme' => array_values($fields),
        ];

        return $this->sendSuccess($schema, 'Data berhasil ditampilkan.');
    }
}
