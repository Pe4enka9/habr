<?php

namespace App\Http\Requests;

class CommentRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'content' => ['required', 'string'],
        ];
    }
}
