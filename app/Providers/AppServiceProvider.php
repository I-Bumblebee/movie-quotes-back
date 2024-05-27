<?php

namespace App\Providers;

use App\Models\Movie;
use App\Policies\MoviePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		Gate::policy(Movie::class, MoviePolicy::class);
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
	}
}
