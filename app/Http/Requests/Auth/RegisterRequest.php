<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class RegisterRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'patronymic' => ['nullable', 'string'],
            'login' => ['required', 'string', Rule::unique(User::class, 'login')],
            'password' => ['required', 'string', 'min:3'],
        ];
    }
}
