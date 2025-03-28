<?php

namespace App\Queries;

use App\Models\Article;
use Illuminate\Support\Collection;

class ArticleQuery
{
    // Сортировка
    public function sort(?array $search): Collection
    {
        $query = Article::query();

        if (!empty($search['search'])) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('text', 'LIKE', '%' . $search['search'] . '%');
            });
        }

        $allowedSortFields = ['name', 'date' => 'date_of_publication', 'rate' => 'rating'];

        $sortBy = $allowedSortFields[$search['sort_by'] ?? 'date'] ?? 'date_of_publication';
        $sortDirection = in_array($search['sort_direction'] ?? 'desc', ['asc', 'desc']) ? $search['sort_direction'] : 'desc';

        $query->orderBy($sortBy, $sortDirection);

        return $query->get();
    }
}
