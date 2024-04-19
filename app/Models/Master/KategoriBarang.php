<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class KategoriBarang
 * @package App\Models
 *
 * @OA\Schema(
 *      @OA\Xml(name="KategoriBarang"),
 *      description="KategoriBarang Model",
 *      type="object",
 *      title="KategoriBarang Model",
 *      @OA\Property(property="id", type="int"),
 *      @OA\Property(property="nama", type="string"),
 *      @OA\Property(property="deleted_at", type="string"),
 *      @OA\Property(property="created_by", type="int"),
 *      @OA\Property(property="updated_by", type="int"),
 *      @OA\Property(property="created_at", type="string"),
 *      @OA\Property(property="updated_at", type="string"),
 * )
 * @property int id
 * @property string nama
 * @property string deleted_at
 * @property int created_by
 * @property int updated_by
 * @property string created_at
 * @property string updated_at
 */
class KategoriBarang extends Model
{
    use HasFactory, SoftDeletes, MultiTenantModelTrait;

    protected $table = 'm_kategori_barang';
	protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'nama',
		'created_by',
		'updated_by',
	];
}
