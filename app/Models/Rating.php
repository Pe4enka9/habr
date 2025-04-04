<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $article_id
 * @property int $rating
 */
class Rating extends Model
{
    protected $guarded = ['id'];
}
