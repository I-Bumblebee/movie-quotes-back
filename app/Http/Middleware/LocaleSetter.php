<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LocaleSetter
{
	public function handle(Request $request, Closure $next): Response
	{
		$locale = $request->cookie('locale') ?: config('app.locale');

		App::setLocale($locale);

		return $next($request);
	}
}
