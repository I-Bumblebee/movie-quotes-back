<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function user(Request $request): JsonResponse
	{
		return response()->json([
			'user' => UserResource::make($request->user()),
		]);
	}

	public function update(UpdateUserRequest $request): JsonResponse
	{
		$user = $request->user();
		$user->update($request->only('name', 'password'));

		if ($request->hasFile('image')) {
			$user->clearMediaCollection('profile_images');
			$user->addMediaFromRequest('image')->toMediaCollection('profile_images');
		}

		return response()->json([
			'user' => UserResource::make($user),
		]);
	}
}
