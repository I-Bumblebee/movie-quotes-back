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
		App::setLocale($request->cookie('locale'));

		return $next($request);
	}
}
