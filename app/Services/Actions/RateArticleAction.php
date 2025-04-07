<?php

namespace App\Services\Actions;

use App\Models\Article;
use App\Models\Rating;

class RateArticleAction
{
    public function handle(Article $article, int $userId, float $rating): void
    {
        $ratingModel = Rating::where('user_id', $userId)
            ->where('article_id', $article->id)
            ->first();

        if (!$ratingModel) {
            Rating::create([
                'user_id' => $userId,
                'article_id' => $article->id,
                'rating' => $rating,
            ]);
        } else {
            $ratingModel->update([
                'rating' => $rating,
            ]);
        }

        $avgRating = $article->ratings()->avg('rating');

        $article->update([
            'rating' => $avgRating,
        ]);
    }
}
