<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class JenisBarang
 * @package App\Models
 *
 * @OA\Schema(
 *      @OA\Xml(name="JenisBarang"),
 *      description="JenisBarang Model",
 *      type="object",
 *      title="JenisBarang Model",
 *      @OA\Property(property="id", type="int"),
 *      @OA\Property(property="m_kategori_barang_id", type="int"),
 *      @OA\Property(property="nama", type="string"),
 *      @OA\Property(property="deleted_at", type="string"),
 *      @OA\Property(property="created_by", type="int"),
 *      @OA\Property(property="updated_by", type="int"),
 *      @OA\Property(property="created_at", type="string"),
 *      @OA\Property(property="updated_at", type="string"),
 * )
 * @property int id
 * @property int m_kategori_barang_id
 * @property string nama
 * @property string deleted_at
 * @property int created_by
 * @property int updated_by
 * @property string created_at
 * @property string updated_at
 */
class JenisBarang extends Model
{
    use HasFactory, SoftDeletes, MultiTenantModelTrait;

    protected $table = 'm_jenis_barang';
	protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'm_kategori_barang_id',
		'nama',
		'created_by',
		'updated_by',
	];
}
