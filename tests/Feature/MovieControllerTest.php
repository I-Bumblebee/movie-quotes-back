<?php

use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

beforeEach(function () {
	Artisan::call('migrate:fresh');
});

it('lists all movies', function () {
	$user = User::factory()->create();
	$movies = Movie::factory()->count(3)->create(['user_id' => $user->id]);

	$response = $this->actingAs($user)->getJson(route('movies.index'));

	$response->assertStatus(200);
	$response->assertJsonCount(3, 'data');
});

it('shows a movie', function () {
	$user = User::factory()->create();
	$movie = Movie::factory()->create(['user_id' => $user->id]);

	$response = $this->actingAs($user)->getJson(route('movies.show', $movie));

	$response->assertStatus(200);
	$response->assertJsonPath('data.id', $movie->id);
});

it('updates a movie', function () {
	$user = User::factory()->create();
	$movie = Movie::factory()->create(['user_id' => $user->id]);
	$genres = Genre::factory()->count(3)->create();

	Storage::fake('public');
	$file = UploadedFile::fake()->image('poster.jpg');

	$data = [
		'title' => [
			'en' => 'NewTitleEn',
			'ka' => 'ახალი სათაური',
		],
		'description' => [
			'en' => 'NewDescriptionEn',
			'ka' => 'ახალი აღწერა',
		],
		'release_year'  => 2024,
		'director_name' => [
			'en' => 'NewDirectorEn',
			'ka' => 'ახალი რეჟისორი',
		],
		'poster' => $file,
		'genres' => $genres->pluck('id')->toArray(),
	];

	$response = $this->actingAs($user)->putJson(route('movies.update', $movie), $data);
	$response->assertStatus(200);

	$this->assertDatabaseHas('movies', [
		'id'            => $movie->id,
		'title'         => json_encode($data['title'], JSON_UNESCAPED_UNICODE),
		'description'   => json_encode($data['description'], JSON_UNESCAPED_UNICODE),
		'release_year'  => $data['release_year'],
		'director_name' => json_encode($data['director_name'], JSON_UNESCAPED_UNICODE),
	]);

	$mediaUrl = $movie->getFirstMediaUrl('posters');
	$mediaUrl = Str::replaceFirst('storage/', '', $mediaUrl);
	Storage::disk('public')->assertExists($mediaUrl);
});

it('deletes a movie', function () {
	$user = User::factory()->create();
	$movie = Movie::factory()->create(['user_id' => $user->id]);

	$response = $this->actingAs($user)->deleteJson(route('movies.destroy', $movie));

	$response->assertStatus(204);
	$this->assertDatabaseMissing('movies', ['id' => $movie->id]);
});

it('creates a movie', function () {
	$user = User::factory()->create();
	$genres = Genre::factory()->count(3)->create();

	Storage::fake('public');
	$file = UploadedFile::fake()->image('poster.jpg');

	$data = [
		'title' => [
			'en' => 'TitleEn',
			'ka' => 'სათაური',
		],
		'description' => [
			'en' => 'DescriptionEn',
			'ka' => 'აღწერა',
		],
		'release_year'  => 2023,
		'director_name' => [
			'en' => 'DirectorEn',
			'ka' => 'რეჟისორი',
		],
		'poster' => $file,
		'genres' => $genres->pluck('id')->toArray(),
	];

	$response = $this->actingAs($user)->postJson(route('movies.store'), $data);
	$response->assertStatus(201);

	$this->assertDatabaseHas('movies', [
		'title'         => json_encode($data['title'], JSON_UNESCAPED_UNICODE),
		'description'   => json_encode($data['description'], JSON_UNESCAPED_UNICODE),
		'release_year'  => $data['release_year'],
		'director_name' => json_encode($data['director_name'], JSON_UNESCAPED_UNICODE),
	]);

	$mediaUrl = Movie::first()->getFirstMediaUrl('posters');
	$mediaUrl = Str::replaceFirst('storage/', '', $mediaUrl);
	Storage::disk('public')->assertExists($mediaUrl);
});

it('does not allow to update a movie if user is not the owner', function () {
	$user = User::factory()->create();
	$movie = Movie::factory()->create();

	$response = $this->actingAs($user)->putJson(route('movies.update', $movie), []);

	$response->assertStatus(403);
});

it('does not allow to delete a movie if user is not the owner', function () {
	$user = User::factory()->create();
	$movie = Movie::factory()->create();

	$response = $this->actingAs($user)->deleteJson(route('movies.destroy', $movie));

	$response->assertStatus(403);
});

it('does not allow to update a movie if user is not authenticated', function () {
	$movie = Movie::factory()->create();

	$response = $this->putJson(route('movies.update', $movie), []);

	$response->assertStatus(401);
});

it('filters movies by title', function () {
	$user = User::factory()->create();
	$movies = Movie::factory()->count(3)->create(['user_id' => $user->id]);

	$response = $this->actingAs($user)->getJson(route('movies.index', ['filter[title]' => $movies[1]->title]));

	$response->assertStatus(200);
	$response->assertJsonCount(1, 'data');
	$response->assertJsonPath('data.0.id', $movies[1]->id);
});
