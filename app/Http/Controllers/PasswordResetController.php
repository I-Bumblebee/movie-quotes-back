<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendResetLinkRequest;
use App\Jobs\SendPasswordResetLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
	public function sendResetLink(SendResetLinkRequest $request): JsonResponse
	{
		dispatch(new SendPasswordResetLink($request->validated()['email']));

		return response()->json(['message' => 'Password reset link sent.']);
	}

	public function resetPassword(ResetPasswordRequest $request): JsonResponse
	{
		$status = Password::reset(
			$request->validated(),
			function (User $user, string $password) {
				$user->forceFill([
					'password' => Hash::make($password),
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);

		if ($status == Password::PASSWORD_RESET) {
			return response()->json(['message' => 'Password reset.']);
		} else {
			return response()->json([
				'message' => 'There was an issue resetting the password. Please try again.',
			], 422);
		}
	}
}
