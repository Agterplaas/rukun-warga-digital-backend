<?php

namespace App\Models\Master;

use App\Models\Pengurus;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MJabatan
 *
 * @OA\Schema(
 *
 *      @OA\Xml(name="MJabatan"),
 *      description="MJabatan Model",
 *      type="object",
 *      title="MJabatan Model",
 *
 *      @OA\Property(property="id", type="int"),
 *      @OA\Property(property="nama", type="string"),
 *      @OA\Property(property="created_by", type="string"),
 *      @OA\Property(property="updated_by", type="string"),
 *      @OA\Property(property="created_at", type="string"),
 *      @OA\Property(property="updated_at", type="string"),
 *      @OA\Property(property="deleted_at", type="string"),
 * )
 *
 * @property int id
 * @property string nama
 * @property string created_by
 * @property string updated_by
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 */
class Jabatan extends Model
{
    use HasFactory, MultiTenantModelTrait, SoftDeletes;

    protected $table = 'm_jabatan';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
    ];

    public function pengurus(): BelongsTo
    {
        return $this->belongsTo(Pengurus::class, 'jabatan_id', 'id');
    }
}
