<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Регистрация
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'patronymic' => $request->patronymic,
            'login' => $request->login,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->createToken('auth')->plainTextToken,
        ], 201);
    }

    // Авторизация
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('login', $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([], 404);
        }

        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->createToken('auth')->plainTextToken,
        ]);
    }

    // Выход
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json(null, 204);
    }

    // Получение профиля пользователя
    public function getUser(Request $request): JsonResponse
    {
        return response()->json(new UserResource($request->user()));
    }
}
