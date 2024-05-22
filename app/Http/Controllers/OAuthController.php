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

	public function handleGoogleCallback(): JsonResponse
	{
		$googleUser = Socialite::driver('google')->stateless()->user();

		$user = User::updateOrCreate([
			'google_id' => $googleUser->id,
		], [
			'name'              => $googleUser->name,
			'email'             => $googleUser->email,
			'email_verified_at' => now(),
		]);

		if ($user->wasRecentlyCreated && isset($googleUser->avatar)) {
			$user->addMediaFromUrl($googleUser->avatar)
				->toMediaCollection('profile_images');
		}
		Auth::login($user, true);

		return response()->json([
			'user' => UserResource::make($user),
		]);
	}
}
