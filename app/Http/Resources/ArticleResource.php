<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Article
 */
class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'text' => $this->text,
            'slug' => $this->slug,
            'image' => $this->image,
            'date_of_publication' => $this->date_of_publication,
            'author' => new UserResource($this->author),
            'rating' => $this->rating,
        ];
    }
}
