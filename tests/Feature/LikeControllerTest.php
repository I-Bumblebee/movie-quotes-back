<?php

use App\Events\NotifyUser;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
	Artisan::call('migrate:fresh');
	$this->quote = Quote::factory()->create();
	$this->user = User::factory()->create();
});

it('should add a like to a quote and dispatch notification', function () {
	Event::fake();

	$response = $this->actingAs($this->user)
		->postJson(route('quotes.like.add', $this->quote->id));

	$response->assertOk();
	$this->assertDatabaseHas('likes', [
		'user_id'  => $this->user->id,
		'quote_id' => $this->quote->id,
	]);
	$this->assertDatabaseHas('notifications', [
		'user_id' => $this->quote->user_id,
	]);
	Event::assertDispatched(NotifyUser::class);
});

it('should remove a like from a quote', function () {
	$this->user->likedQuotes()->attach($this->quote->id);

	$response = $this->actingAs($this->user)
		->deleteJson(route('quotes.like.remove', $this->quote->id));

	$response->assertOk();
	$this->assertDatabaseMissing('likes', [
		'user_id'  => $this->user->id,
		'quote_id' => $this->quote->id,
	]);
});
