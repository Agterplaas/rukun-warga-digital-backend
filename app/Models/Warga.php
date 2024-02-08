<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Warga
 * @package App\Models
 *
 * @OA\Schema(
 *      @OA\Xml(name="Warga"),
 *      description="Warga Model",
 *      type="object",
 *      title="Warga Model",
 *      @OA\Property(property="id", type="int"),
 *      @OA\Property(property="no_kk", type="string"),
 *      @OA\Property(property="nik", type="string"),
 *      @OA\Property(property="nama", type="string"),
 *      @OA\Property(property="jenis_kelamin", type="int"),
 *      @OA\Property(property="tgl_lahir", type="string"),
 *      @OA\Property(property="alamat_ktp", type="string"),
 *      @OA\Property(property="blok", type="string"),
 *      @OA\Property(property="nomor", type="int"),
 *      @OA\Property(property="rt", type="int"),
 *      @OA\Property(property="agama", type="bool"),
 *      @OA\Property(property="pekerjaan", type="string"),
 *      @OA\Property(property="no_telp", type="string"),
 *      @OA\Property(property="status_warga", type="string"),
 *      @OA\Property(property="status_kawin", type="int"),
 *      @OA\Property(property="status_sosial", type="string"),
 *      @OA\Property(property="catatan", type="string"),
 *      @OA\Property(property="kk_pj", type="int"),
 *      @OA\Property(property="created_by", type="string"),
 *      @OA\Property(property="updated_by", type="string"),
 *      @OA\Property(property="created_at", type="string"),
 *      @OA\Property(property="updated_at", type="string"),
 *      @OA\Property(property="deleted_at", type="string"),
 * )
 * @property int id
 * @property string no_kk
 * @property string nik
 * @property string nama
 * @property int jenis_kelamin
 * @property string tgl_lahir
 * @property string alamat_ktp
 * @property string blok
 * @property int nomor
 * @property int rt
 * @property bool agama
 * @property string pekerjaan
 * @property string no_telp
 * @property string status_warga
 * @property int status_kawin
 * @property string status_sosial
 * @property string catatan
 * @property int kk_pj
 * @property string created_by
 * @property string updated_by
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 */
class Warga extends Model
{
    use HasFactory, SoftDeletes, MultiTenantModelTrait;

    protected $table = 'warga';
	protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'no_kk',
		'nik',
		'nama',
		'jenis_kelamin',
		'tgl_lahir',
		'alamat_ktp',
		'blok',
		'nomor',
		'rt',
		'agama',
		'pekerjaan',
		'no_telp',
		'status_warga',
		'status_kawin',
		'status_sosial',
		'catatan',
		'kk_pj',
		'created_by',
		'updated_by',
	];
}
