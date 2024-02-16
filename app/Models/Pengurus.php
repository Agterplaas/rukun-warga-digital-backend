<?php

namespace App\Models;

use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Pengurus
 *
 * @OA\Schema(
 *
 *      @OA\Xml(name="Pengurus"),
 *      description="Pengurus Model",
 *      type="object",
 *      title="Pengurus Model",
 *
 *      @OA\Property(property="id", type="int"),
 *      @OA\Property(property="warga_id", type="int"),
 *      @OA\Property(property="jabatan_id", type="int"),
 *      @OA\Property(property="created_by", type="string"),
 *      @OA\Property(property="updated_by", type="string"),
 *      @OA\Property(property="created_at", type="string"),
 *      @OA\Property(property="updated_at", type="string"),
 *      @OA\Property(property="deleted_at", type="string"),
 * )
 *
 * @property int id
 * @property int warga_id
 * @property int jabatan_id
 * @property string created_by
 * @property string updated_by
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 */
class Pengurus extends Model
{
    use HasFactory, MultiTenantModelTrait, SoftDeletes;

    protected $table = 'pengurus';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'warga_id',
        'jabatan_id',
        'created_by',
        'updated_by',
    ];

    protected $cast = [
        'jabatan_id' => 'array',
    ];

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class);
    }

    public function jabatan(): HasMany
    {
        return $this->hasMany(MJabatan::class, 'id', 'jabatan_id');
    }
}
