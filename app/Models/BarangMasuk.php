<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BarangMasuk
 * @package App\Models
 *
 * @OA\Schema(
 *      @OA\Xml(name="BarangMasuk"),
 *      description="BarangMasuk Model",
 *      type="object",
 *      title="BarangMasuk Model",
 *      @OA\Property(property="id", type="int"),
 *      @OA\Property(property="role_id", type="int"),
 *      @OA\Property(property="jenis_barang", type="string"),
 *      @OA\Property(property="jml_barang", type="int"),
 *      @OA\Property(property="catatan", type="string"),
 *      @OA\Property(property="storage", type="string"),
 *      @OA\Property(property="kepemilikan", type="string"),
 *      @OA\Property(property="created_by", type="int"),
 *      @OA\Property(property="updated_by", type="int"),
 *      @OA\Property(property="deleted_at", type="string"),
 *      @OA\Property(property="created_at", type="string"),
 *      @OA\Property(property="updated_at", type="string"),
 * )
 * @property int id
 * @property int role_id
 * @property string jenis_barang
 * @property int jml_barang
 * @property string catatan
 * @property string storage
 * @property string kepemilikan
 * @property int created_by
 * @property int updated_by
 * @property string deleted_at
 * @property string created_at
 * @property string updated_at
 */
class BarangMasuk extends Model
{
    use HasFactory, SoftDeletes, MultiTenantModelTrait;

    protected $table = 'barang_masuk';
	protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'role_id',
		'jenis_barang',
		'jml_barang',
		'catatan',
		'storage',
		'kepemilikan',
		'created_by',
		'updated_by',
	];
}
