<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendEmailVerificationRequest;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
	public function verify(User $user, string $hash): JsonResponse
	{
		if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
			return response()->json(['message' => 'Invalid verification link.'], 400);
		}

		$user->markEmailAsVerified();

		return response()->json(['message' => 'Email verified.']);
	}

	public function sendVerificationEmail(SendEmailVerificationRequest $request): JsonResponse
	{
		$user = Auth::getProvider()->retrieveByCredentials($request->only('email'));

		if ($user->hasVerifiedEmail()) {
			return response()->json([
				'message' => 'The email is already verified.',
				'errors'  => [
					'email' => trans('validations.email.already_verified'),
				],
			], 400);
		}

		$user->notify(new VerifyEmailNotification());

		return response()->json(['message' => 'Verification email sent.']);
	}
}
