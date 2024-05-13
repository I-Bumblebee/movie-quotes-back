<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailVerified
{
	public function handle(Request $request, Closure $next): Response
	{
		$user = Auth::getProvider()->retrieveByCredentials($request->only('email'));

		if ($user && !$user->hasVerifiedEmail()) {
			return response()->json([
				'errors'  => [
					'email' => trans('validations.email.not_verified'),
				],
			], 401);
		}

		return $next($request);
	}
}
