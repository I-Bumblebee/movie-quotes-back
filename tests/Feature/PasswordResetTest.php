<?php

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

beforeEach(function () {
	Artisan::call('migrate:fresh');
	$this->user = User::factory()->create();
});

it('sends a password reset link to the user', function () {
	Notification::fake();
	$this->postJson(route('password.email'), [
		'email' => $this->user->email,
	])->assertOk();

	Notification::assertSentTo($this->user, ResetPasswordNotification::class);
});

it('resets the user password', function () {
	$token = Password::createToken($this->user);

	$this->postJson(route('password.update'), [
		'email'                 => $this->user->email,
		'token'                 => $token,
		'password'              => 'new-password',
		'password_confirmation' => 'new-password',
	])->assertOk();

	$this->assertTrue(Hash::check('new-password', $this->user->fresh()->password));
});

it('sends a password reset link with valid token that allows users to reset password', function () {
	Notification::fake();

	$this->postJson(route('password.email'), [
		'email' => $this->user->email,
	])->assertOk();

	$token = null;
	Notification::assertSentTo($this->user, ResetPasswordNotification::class, function ($notification) use (&$token) {
		$token = $notification->token;
		return true;
	});

	$this->postJson(route('password.update'), [
		'email'                 => $this->user->email,
		'token'                 => $token,
		'password'              => 'new-password',
		'password_confirmation' => 'new-password',
	])->assertOk();

	$this->assertTrue(Hash::check('new-password', $this->user->fresh()->password));
});

it('does not send a password reset link to the user with invalid email', function () {
	Notification::fake();
	$this->postJson(route('password.email'), [
		'email' => 'invalidEmail@example.com',
	])->assertStatus(422);

	Notification::assertNothingSent();
});

it('does not reset the user password with invalid token', function () {
	$this->postJson(route('password.update'), [
		'email'                 => $this->user->email,
		'token'                 => 'invalid-token',
		'password'              => 'new-password',
		'password_confirmation' => 'new-password',
	])->assertStatus(422);

	$this->assertFalse(Hash::check('new-password', $this->user->fresh()->password));
});
