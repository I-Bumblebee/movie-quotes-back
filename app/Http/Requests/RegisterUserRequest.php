<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name'                  => 'required|min:3|max:15|alpha_num|unique:users',
			'email'                 => 'required|email|unique:users',
			'password'              => 'required|min:8|max:15|alpha_num|confirmed',
			'password_confirmation' => 'required',
		];
	}
}
