<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $image
 * @property Carbon $date_of_publication
 * @property int $author_id
 * @property int $rating
 *
 * @property-read Comment<Collection> $comments
 * @property-read User $author
 */
class Article extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'date_of_publication' => 'datetime',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'article_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
