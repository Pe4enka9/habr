<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [UserController::class, 'register']);
Route::post('/auth/login', [UserController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [UserController::class, 'logout']);

    Route::get('/personal/me', [UserController::class, 'getUser']);

    Route::get('/personal/articles', [ArticleController::class, 'userArticles']);
    Route::post('/personal/articles', [ArticleController::class, 'store']);
    Route::get('/personal/articles/{article:slug}', [ArticleController::class, 'userShow']);
    Route::put('/personal/articles/{article:slug}', [ArticleController::class, 'update']);
    Route::delete('/personal/articles/{article:slug}', [ArticleController::class, 'destroy']);

    Route::post('/articles/{article:slug}/rate', [ArticleController::class, 'rate']);
    Route::post('/articles/{article:slug}/comments', [ArticleController::class, 'storeComment']);
    Route::delete('/articles/comments/{comment:id}', [ArticleController::class, 'destroyComment'])->middleware(AdminMiddleware::class);
});

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{article:slug}', [ArticleController::class, 'show']);
