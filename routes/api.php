<?php

use App\Http\Controllers\OAuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
	Route::prefix('oauth')->controller(OAuthController::class)->group(function () {
		Route::get('/google/redirect', 'getGoogleRedirectUrl')->name('oauth.google.redirectUrl');
		Route::get('/google/callback', 'getGoogleCallback')->name('oauth.google.callback');
	});
	Route::prefix('email')->controller(VerifyEmailController::class)->group(function () {
		Route::get('/verify/{user}/{hash}', 'verify')->middleware('signed:relative')->name('verification.verify');
		Route::post('/verification-notification', 'sendVerificationEmail')->name('verification.send');
	});

	Route::controller(SessionController::class)->group(function () {
		Route::post('/login', 'login')->middleware('ensure-email-verified')->name('login');
		Route::post('/register', 'register')->name('register');
	});

    Route::controller(PasswordResetController::class)->group(function () {
        Route::post('/forgot-password', 'sendResetLink')->name('password.email');
        Route::post('/reset-password', 'resetPassword')->name('password.update');
    });
});

Route::post('/logout', [SessionController::class, 'logout'])->name('logout');
