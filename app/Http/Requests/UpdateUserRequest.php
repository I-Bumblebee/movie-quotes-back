<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'image'                 => 'image',
			'name'                  => 'min:3|max:15|alpha_num|unique:users',
			'password'              => 'min:8|max:15|alpha_num|confirmed',
			'password_confirmation' => 'required_with:password',
		];
	}
}
