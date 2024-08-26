<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'email'    => 'required_without:name|email|exists:users,email',
			'name'     => 'required_without:email|string:users,name',
			'password' => 'required|string',
			'remember' => 'boolean',
		];
	}
}
