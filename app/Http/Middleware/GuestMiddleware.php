<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class GuestMiddleware
{
	public function handle($request, Closure $next)
	{
		if (Auth::check()) {
			return response()->json(['message' => 'User already authenticated.'], 401);
		}

		return $next($request);
	}
}
