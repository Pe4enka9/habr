<?php

namespace App\Services\Actions\Dtos;

use App\Models\User;
use Illuminate\Http\UploadedFile;

readonly class StoreArticleDto
{
    public function __construct(
        public string $name,
        public string $text,
        public ?string $slug,
        public User $user,
        public ?UploadedFile $file,
    )
    {
    }
}
