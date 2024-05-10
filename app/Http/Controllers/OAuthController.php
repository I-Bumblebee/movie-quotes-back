<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
	public function getGoogleRedirectUrl(): JsonResponse
	{
		return response()->json([
			'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
		]);
	}

	public function getGoogleCallback(): JsonResponse
	{
		$googleUser = Socialite::driver('google')->stateless()->user();

		$user = User::updateOrCreate([
			'google_id' => $googleUser->id,
		], [
			'name'              => $googleUser->name,
			'email'             => $googleUser->email,
			'image'             => $googleUser->avatar,
			'email_verified_at' => now(),
		]);

		Auth::login($user);

		return response()->json([
			'user' => UserResource::make($user),
		]);
	}
}
