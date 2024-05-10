<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Laravel\Socialite\Facades\Socialite;

beforeEach(function () {
	Artisan::call('migrate');
});

it('handles google callback', function () {
	$client = Mockery::mock('alias:' . Socialite::class);

	$client->shouldReceive('driver->stateless->user')->andReturn((object) [
		'id'           => '12345',
		'name'         => 'Test User',
		'email'        => 'test@example.com',
		'avatar'       => 'https://example.com/avatar.jpg',
	]);

	$response = $this->getJson(route('oauth.google.callback'));

	$response->assertStatus(200);
	$this->assertDatabaseHas('users', [
		'google_id' => '12345',
	]);
});

it('generates google redirect URL', function () {
	$client = Mockery::mock('alias:' . Socialite::class);

	$client->shouldReceive('driver->stateless->redirect->getTargetUrl')->andReturn('https://example.com');

	$response = $this->getJson(route('oauth.google.redirectUrl'));

	$response->assertStatus(200);
	$response->assertJson(['url' => 'https://example.com']);
});

it('does not create duplicate google users', function () {
	$client = Mockery::mock('alias:' . Socialite::class);

	$client->shouldReceive('driver->stateless->user')->andReturn((object) [
		'id'           => '12345',
		'name'         => 'Test User',
		'email'        => 'test@example.com',
		'avatar'       => 'https://example.com/avatar.jpg',
	]);

	$this->getJson(route('oauth.google.callback'));
	$this->getJson(route('oauth.google.callback'));

	$this->assertDatabaseCount('users', 1);
});

it('does not allow access to google callback without being guest', function () {
	$this->actingAs(User::factory()->create());

	$response = $this->getJson(route('oauth.google.callback'));

	$response->assertStatus(401);
});

it('does not allow access to google redirect URL without being guest', function () {
	$this->actingAs(User::factory()->create());

	$response = $this->getJson(route('oauth.google.redirectUrl'));

	$response->assertStatus(401);
});
