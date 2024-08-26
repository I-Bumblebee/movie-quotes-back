<?php

use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

beforeEach(function () {
	Artisan::call('migrate:fresh');
	$this->user = User::factory()->create();
});

it('creates new quote', function () {
	$movie = Movie::factory()->create();

	$response = $this->actingAs($this->user)->postJson(route('quotes.store'), [
		'quote'    => ['en' => 'New quote', 'ka' => 'ახალი ციტატა'],
		'image'    => UploadedFile::fake()->image('image.jpg'),
		'movie_id' => $movie->id,
	]);

	$response->assertCreated();
	$this->assertDatabaseHas('quotes', [
		'quote'    => json_encode(['en' => 'New quote', 'ka' => 'ახალი ციტატა'], JSON_UNESCAPED_UNICODE),
		'movie_id' => $movie->id,
	]);

	$mediaUrl = Movie::first()->getFirstMediaUrl('quote_images');
	$mediaUrl = Str::replaceFirst('storage/', '', $mediaUrl);
	Storage::disk('public')->assertExists($mediaUrl);
});

it('lists all the quotes', function () {
	$quotes = Quote::factory()->count(3)->create();

	$response = $this->actingAs($this->user)->getJson(route('quotes.index'));

	$response->assertOk();
	$response->assertJsonCount(3, 'data');
});

it('filters quotes by content', function () {
	$quote = Quote::factory()->create(['quote' => ['en' => 'Quote', 'ka' => 'ციტატა']]);
	$quote2 = Quote::factory()->create(['quote' => ['en' => 'Another one here', 'ka' => 'სხვა ერთი აქ']]);

	$response = $this->actingAs($this->user)
		->getJson(route('quotes.index', ['filter[quote]' => 'Quote']));

	$response->assertJsonCount(1, 'data');
	$response->assertJsonPath('data.0.id', $quote->id);
});

it('filters quotes by movie title', function () {
	$movie = Movie::factory()->create(['title' => 'Movie']);
	$quote = Quote::factory()->create(['movie_id' => $movie->id]);
	$quote2 = Quote::factory()->create();

	$response = $this->actingAs($this->user)
		->getJson(route('quotes.index', ['filter[movie.title]' => 'Movie']));

	$response->assertJsonCount(1, 'data');
	$response->assertJsonPath('data.0.id', $quote->id);
});

it('shows a quote', function () {
	$quote = Quote::factory()->create();

	$response = $this->actingAs($this->user)->getJson(route('quotes.show', $quote->id));

	$response->assertOk();
	$response->assertJsonPath('data.id', $quote->id);
});

it('updates a quote', function () {
	$quote = Quote::factory()->create(['user_id' => $this->user->id]);

	$response = $this->actingAs($this->user)->patchJson(route('quotes.update', $quote->id), [
		'quote'    => ['en' => 'Updated quote', 'ka' => 'განახლებული ციტატა'],
		'image'    => UploadedFile::fake()->image('image.jpg'),
	]);

	$response->assertOk();
	$this->assertDatabaseHas('quotes', [
		'id'    => $quote->id,
		'quote' => json_encode(['en' => 'Updated quote', 'ka' => 'განახლებული ციტატა'], JSON_UNESCAPED_UNICODE),
	]);
});

it('deletes a quote', function () {
	$quote = Quote::factory()->create(['user_id' => $this->user->id]);
	$response = $this->actingAs($this->user)->deleteJson(route('quotes.destroy', $quote->id));

	$response->assertNoContent();
	$this->assertDatabaseMissing('quotes', ['id' => $quote->id]);
});

it('does not allow to update a quote if user is not the owner', function () {
	$quote = Quote::factory()->create();
	$response = $this->actingAs(User::factory()->create())->patchJson(route('quotes.update', $quote->id), [
		'quote'    => ['en' => 'Updated quote', 'ka' => 'განახლებული ციტატა'],
		'image'    => UploadedFile::fake()->image('image.jpg'),
	]);

	$response->assertForbidden();
});

it('does not allow to delete a quote if user is not the owner', function () {
	$quote = Quote::factory()->create();
	$response = $this->actingAs(User::factory()->create())->deleteJson(route('quotes.destroy', $quote->id));

	$response->assertForbidden();
});
