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

    public function show(BarangPinjam $barangPinjam)
    {
        return $this->sendSuccess(new BarangPinjamResource($barangPinjam), 'Data berhasil ditampilkan.');
    }

    public function update(StoreBarangPinjamRequest $request, BarangPinjam $barangPinjam)
    {
        $barangPinjam->update($request->all());

        return $this->sendSuccess(new BarangPinjamResource($barangPinjam), 'Data sukses disimpan.');
    }

    public function destroy(BarangPinjam $barangPinjam)
    {
        $barangPinjam->delete();

        return $this->sendSuccess([], null, 204);
    }

    public function schema(Request $request)
    {
        $fields = DB::select('describe barang_pinjam');
        $schema = [
            'name' => 'barang_pinjam',
            'module' => 'BarangPinjam',
            'primary_key' => 'id',
            'endpoint' => '/barang-pinjams',
            'scheme' => array_values($fields),
        ];

        return $this->sendSuccess($schema, 'Data berhasil ditampilkan.');
    }
}
