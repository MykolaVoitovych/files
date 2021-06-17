<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *      title="Tag",
 *      type="object",
 *      @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *      @OA\Property(property="name", type="string", maxLength=255, example="TagName"),
 * )
 */
class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function files()
    {
        return $this->belongsToMany(File::class);
    }
}
