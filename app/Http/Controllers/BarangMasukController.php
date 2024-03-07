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

    public function show(BarangMasuk $barangMasuk)
    {
        return $this->sendSuccess(new BarangMasukResource($barangMasuk), 'Data berhasil ditampilkan.');
    }

    public function update(StoreBarangMasukRequest $request, BarangMasuk $barangMasuk)
    {
        $barangMasuk->update($request->all());

        return $this->sendSuccess(new BarangMasukResource($barangMasuk), 'Data sukses disimpan.');
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        $barangMasuk->delete();

        return $this->sendSuccess([], null, 204);
    }

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
