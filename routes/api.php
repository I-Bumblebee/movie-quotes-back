<?php

use App\Http\Controllers\OAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
	Route::prefix('oauth')->group(function () {
		Route::get('/google/redirect', [OAuthController::class, 'getGoogleRedirectUrl'])->name('oauth.google.redirectUrl');
		Route::get('/google/callback', [OAuthController::class, 'getGoogleCallback'])->name('oauth.google.callback');
	});
});
