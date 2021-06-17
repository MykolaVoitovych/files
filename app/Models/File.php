<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @OA\Schema(
 *      title="File",
 *      type="object",
 *      @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *      @OA\Property(property="name", type="string", maxLength=255, example="FileName"),
 * )
 */
class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeDoesntHaveTags(Builder $query, array $tags): Builder
    {
        return $query->whereDoesntHave('tags', function (Builder $query) use ($tags) {
            $query->whereIn('name', $tags);
        });
    }

    public function scopeMustHaveTags(Builder $query, array $tags): Builder
    {
        foreach ($tags as $tag) {
            $query->whereHas('tags', function ($query) use ($tag) {
                $query->where('name', $tag);
            });
        }
        return $query;
    }
}
