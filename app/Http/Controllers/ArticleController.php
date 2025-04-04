<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CommentResource;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Rating;
use App\Queries\ArticleQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Str;

class ArticleController extends Controller
{
    // Получение списка статей
    public function index(Request $request): JsonResponse
    {
        $articles = (new ArticleQuery())->sort($request->all());

        return response()->json(ArticleResource::collection($articles));
    }

    // Получение статьи по slug
    public function show(Article $article): JsonResponse
    {
        return response()->json([
            'article' => new ArticleResource($article),
            'comments' => CommentResource::collection($article->comments),
        ]);
    }

    // Получение статей пользователя
    public function userArticles(Request $request): JsonResponse
    {
        $articles = Article::where('author_id', $request->user()->id)->get();

        return response()->json(ArticleResource::collection($articles));
    }

    // Создание статьи
    public function store(ArticleRequest $request): JsonResponse
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $fullPath = Storage::disk('public')->url($path);
        }

        $article = Article::create([
            'name' => $request->name,
            'text' => $request->text,
            'slug' => $request->slug ?? Str::slug($request->name),
            'image' => $fullPath ?? null,
            'date_of_publication' => now(),
            'author_id' => $request->user()->id,
        ]);

        return response()->json(new ArticleResource($article), 201);
    }

    // Получение статьи пользователя по slug
    public function userShow(Article $article): JsonResponse
    {
        return response()->json(new ArticleResource($article));
    }

    // Обновление статьи
    public function update(Article $article, ArticleRequest $request): JsonResponse
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
        }

        $article->update([
            'name' => $request->name,
            'text' => $request->text,
            'image' => $path ?? $article->image,
            'slug' => $request->slug ?? Str::slug($request->name),
        ]);

        return response()->json(new ArticleResource($article));
    }

    // Удаление статьи
    public function destroy(Article $article): JsonResponse
    {
        $article->delete();

        return response()->json(null, 204);
    }

    // Оценка статьи
    public function rate(Article $article, Request $request): JsonResponse
    {
        $rating = Rating::where('user_id', $request->user()->id)
            ->where('article_id', $article->id)
            ->first();

        if (!$rating) {
            Rating::create([
                'user_id' => $request->user()->id,
                'article_id' => $article->id,
                'rating' => $request->input('rating'),
            ]);
        } else {
            $rating->update([
                'rating' => $request->input('rating'),
            ]);
        }

        $avgRating = $article->ratings()->avg('rating');

        $article->update([
            'rating' => $avgRating,
        ]);

        return response()->json(new ArticleResource($article));
    }

    // Добавление комментария
    public function storeComment(Article $article, CommentRequest $request): JsonResponse
    {
        $comment = Comment::create([
            'article_id' => $article->id,
            'user_id' => $request->user()->id,
            'content' => $request->get('content'),
        ]);

        return response()->json(new CommentResource($comment), 201);
    }

    // Удаление комментария
    public function destroyComment(Comment $comment): JsonResponse
    {
        $comment->delete();

        return response()->json(null, 204);
    }
}
