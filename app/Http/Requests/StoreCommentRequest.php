<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'comment' => 'required|string|max:255',
        ];
    }
}
