<?php

namespace App\Services\Actions;

use App\Models\Article;
use App\Services\Actions\Dtos\StoreArticleDto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreArticleAction
{
    public function handle(StoreArticleDto $dto): Article
    {
        if ($dto->file) {
            $path = $dto->file->store('articles', 'public');
            $fullPath = Storage::disk('public')->url($path);
        }

        return Article::create([
            'name' => $dto->name,
            'text' => $dto->text,
            'slug' => $dto->slug ?? Str::slug($dto->name),
            'image' => $fullPath ?? null,
            'date_of_publication' => now(),
            'author_id' => $dto->user->id,
        ]);
    }
}
