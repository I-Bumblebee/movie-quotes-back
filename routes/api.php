<?php

use App\Http\Controllers\OAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
	Route::prefix('oauth')->controller(OAuthController::class)->group(function () {
		Route::get('/google/redirect', 'getGoogleRedirectUrl')->name('oauth.google.redirectUrl');
		Route::get('/google/callback', 'getGoogleCallback')->name('oauth.google.callback');
	});
});
