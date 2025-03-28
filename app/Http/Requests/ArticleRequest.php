<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Validation\Rule;

class ArticleRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'text' => ['required', 'string'],
            'slug' => ['nullable', 'string', Rule::unique(Article::class, 'slug')],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ];
    }
}
