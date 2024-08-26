<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
	Artisan::call('migrate:fresh');
});

it('requires email verification before login', function () {
	$user = User::factory()->create([
		'email_verified_at' => null,
	]);

	$this->postJson(route('login'), [
		'email'    => $user->email,
		'password' => 'password',
	])->assertStatus(401);
});

it('allows login for verified users', function () {
	$user = User::factory()->create([
		'email_verified_at' => now(),
	]);

	$this->postJson(route('login'), [
		'email'    => $user->email,
		'password' => 'password',
	])->assertStatus(200);

	$this->assertAuthenticated();
});

it('allows users to logout', function () {
	$user = User::factory()->create([
		'email_verified_at' => now(),
	]);

	$this->actingAs($user)->postJson(route('logout'))->assertStatus(200);
	$this->assertGuest();
});

it('does not allow login with incorrect password', function () {
	$user = User::factory()->create([
		'email_verified_at' => now(),
	]);

	$this->postJson(route('login'), [
		'email'    => $user->email,
		'password' => 'wrong-password',
	])->assertStatus(401);
});

it('does not allow login with unregistered email', function () {
	$this->postJson(route('login'), [
		'email'    => 'unregistered@example.com',
		'password' => 'password',
	])->assertStatus(422);
});
