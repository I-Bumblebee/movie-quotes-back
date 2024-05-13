<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
	public function register(RegisterUserRequest $request): JsonResponse
	{
		$user = User::create($request->validated());

		event(new Registered($user));

		return response()->json(['message' => 'User registered!'], 201);
	}

	public function login(LoginUserRequest $request): JsonResponse
	{
		$credentials = $request->only(['email', 'password']);
		$remember = $request->boolean('remember');

		if (!Auth::attempt($credentials, $remember)) {
			return response()->json([
				'errors'  => [
					'email'    => trans('auth.failed'),
					'password' => trans('auth.failed'),
				],
			], 401);
		}

		return response()->json([
			'user'    => UserResource::make(Auth::user()),
		]);
	}

	public function logout(): JsonResponse
	{
		Auth::guard('web')->logout();
		return response()->json(['message' => 'Logged out.']);
	}
}
