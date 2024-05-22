<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

beforeEach(function () {
	Artisan::call('migrate:fresh');
});

it('updates the user', function () {
	$user = User::factory()->create();

	Storage::fake('public');
	$file = UploadedFile::fake()->image('avatar.jpg');

	$data = [
		'name'                  => 'NewName',
		'password'              => 'NewPassword123',
		'password_confirmation' => 'NewPassword123',
		'image'                 => $file,
	];

	$response = $this->actingAs($user)->postJson(route('user.update'), $data);
	$response->assertStatus(200);

	$this->assertDatabaseHas('users', [
		'id'   => $user->id,
		'name' => 'NewName',
	]);

	$mediaUrl = $user->getFirstMediaUrl('profile_images');
	$mediaUrl = Str::replaceFirst('storage/', '', $mediaUrl);
	Storage::disk('public')->assertExists($mediaUrl);
});

it('fails validation', function ($data, $expectedErrors) {
	$user = User::factory()->create();

	$response = $this->actingAs($user)->postJson(route('user.update'), $data);
	$response->assertStatus(422);
	$response->assertJsonValidationErrors($expectedErrors);
})->with('validationData');

it('does not allow access to user update without being authenticated', function () {
	$user = User::factory()->create();

	$response = $this->postJson(route('user.update'), []);
	$response->assertStatus(401);
});

it('does not allow duplicate usernames', function () {
	$user1 = User::factory()->create(['name' => 'TestName']);
	$user2 = User::factory()->create();

	$response = $this->actingAs($user2)->postJson(route('user.update'), ['name' => 'TestName']);
	$response->assertStatus(422);
	$response->assertJsonValidationErrors('name');
});

dataset('validationData', [
	'missing password confirmation' => [
		'data' => [
			'name'     => 'NewName',
			'password' => 'NewPassword123',
		],
		'expectedErrors' => ['password_confirmation'],
	],
	'invalid image type' => [
		'data' => [
			'name'                  => 'NewName',
			'password'              => 'NewPassword123',
			'password_confirmation' => 'NewPassword123',
			'image'                 => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
		],
		'expectedErrors' => ['image'],
	],
	'invalid name (too short)' => [
		'data' => [
			'name'                  => 'ab',
			'password'              => 'NewPassword123',
			'password_confirmation' => 'NewPassword123',
		],
		'expectedErrors' => ['name'],
	],
	'invalid name (too long)' => [
		'data' => [
			'name'                  => 'abcdefghijklmnop',
			'password'              => 'NewPassword123',
			'password_confirmation' => 'NewPassword123',
		],
		'expectedErrors' => ['name'],
	],
	'invalid name (not alphanumeric)' => [
		'data' => [
			'name'                  => 'New Name!',
			'password'              => 'NewPassword123',
			'password_confirmation' => 'NewPassword123',
		],
		'expectedErrors' => ['name'],
	],
	'invalid password (too short)' => [
		'data' => [
			'name'                  => 'NewName',
			'password'              => 'short1',
			'password_confirmation' => 'short1',
		],
		'expectedErrors' => ['password'],
	],
	'invalid password (too long)' => [
		'data' => [
			'name'                  => 'NewName',
			'password'              => 'thisisaverylongpassword1',
			'password_confirmation' => 'thisisaverylongpassword1',
		],
		'expectedErrors' => ['password'],
	],
	'invalid password (not alphanumeric)' => [
		'data' => [
			'name'                  => 'NewName',
			'password'              => 'password!',
			'password_confirmation' => 'password!',
		],
		'expectedErrors' => ['password'],
	],
]);
