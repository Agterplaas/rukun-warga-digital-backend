<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BarangPinjam
 * @package App\Models
 *
 * @OA\Schema(
 *      @OA\Xml(name="BarangPinjam"),
 *      description="BarangPinjam Model",
 *      type="object",
 *      title="BarangPinjam Model",
 *      @OA\Property(property="id", type="int"),
 *      @OA\Property(property="acara_id", type="int"),
 *      @OA\Property(property="jenis_barang", type="string"),
 *      @OA\Property(property="jml_barang", type="int"),
 *      @OA\Property(property="catatan", type="string"),
 *      @OA\Property(property="storage", type="string"),
 *      @OA\Property(property="kepemilikan", type="string"),
 *      @OA\Property(property="deleted_at", type="string"),
 *      @OA\Property(property="created_by", type="int"),
 *      @OA\Property(property="updated_by", type="int"),
 *      @OA\Property(property="created_at", type="string"),
 *      @OA\Property(property="updated_at", type="string"),
 * )
 * @property int id
 * @property int acara_id
 * @property string jenis_barang
 * @property int jml_barang
 * @property string catatan
 * @property string storage
 * @property string kepemilikan
 * @property string deleted_at
 * @property int created_by
 * @property int updated_by
 * @property string created_at
 * @property string updated_at
 */
class BarangPinjam extends Model
{
    use HasFactory, SoftDeletes, MultiTenantModelTrait;

    protected $table = 'barang_pinjam';
	protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'acara_id',
		'jenis_barang',
		'jml_barang',
		'catatan',
		'storage',
		'kepemilikan',
		'created_by',
		'updated_by',
	];


    /**
     * Get the user that owns the BarangPinjam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function barang_masuk(): BelongsTo
    {
        return $this->belongsTo(BarangMasuk::class);
    }
}
