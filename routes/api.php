<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
	Route::prefix('oauth')->controller(OAuthController::class)->group(function () {
		Route::get('/google/redirect', 'getGoogleRedirectUrl')->name('oauth.google.redirectUrl');
		Route::get('/google/callback', 'handleGoogleCallback')->name('oauth.google.callback');
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
Route::middleware('auth:sanctum')->group(function () {
	Route::get('/user', [UserController::class, 'user'])->name('user');
	Route::patch('/user', [UserController::class, 'update'])->name('user.update');

	Route::prefix('movies')->controller(MovieController::class)->group(function () {
		Route::get('/', 'index')->name('movies.index');
		Route::get('/{movie}', 'show')->name('movies.show');
		Route::get('/{movie}/quotes', 'quotes')->name('movies.quotes');

		Route::post('/', 'store')->name('movies.store');

		Route::put('/{movie}', 'update')->name('movies.update');
		Route::delete('/{movie}', 'destroy')->name('movies.destroy');
	});

	Route::prefix('quotes')->group(function () {
		Route::controller(QuoteController::class)->group(function () {
			Route::get('/', 'index')->name('quotes.index');
			Route::get('/{quote}', 'show')->name('quotes.show');
			Route::post('/', 'store')->name('quotes.store');
			Route::patch('/{quote}', 'update')->name('quotes.update');
            Route::delete('/{quote}', 'destroy')->name('quotes.destroy');
		});

		Route::controller(CommentController::class)->group(function () {
			Route::post('/{quote}/comments', 'addComment')->name('quotes.comments.add');
		});
		Route::controller(LikeController::class)->group(function () {
			Route::post('/{quote}/like', 'addLike')->name('quotes.like.add');
			Route::delete('/{quote}/like', 'removeLike')->name('quotes.like.remove');
		});
	});

	Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
		Route::get('/', 'index')->name('notifications.index');
		Route::patch('/mark-as-read', 'markAsRead')->name('notifications.markAsRead');
	});
});

Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
