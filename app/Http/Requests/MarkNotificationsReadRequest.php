<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarkNotificationsReadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'notification_ids'   => 'required|array',
            'notification_ids.*' => 'required|integer|exists:notifications,id',
        ];
    }
}
