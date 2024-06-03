<?php

namespace App\Providers;

use App\Models\Movie;
use App\Models\Quote;
use App\Policies\MoviePolicy;
use App\Policies\QuotePolicy;
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
        Gate::policy(Quote::class, QuotePolicy::class);
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
	}
}
