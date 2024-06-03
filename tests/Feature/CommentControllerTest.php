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

it('should add a comment to a quote and dispatch notification', function () {
	Event::fake();
	$response = $this->actingAs($this->user)
		->postJson(route('quotes.comments.add', $this->quote->id), [
			'comment' => 'This is a comment',
		]);

	$response->assertOk();
	$this->assertDatabaseHas('comments', [
		'user_id'  => $this->user->id,
		'quote_id' => $this->quote->id,
		'comment'  => 'This is a comment',
	]);
	$this->assertDatabaseHas('notifications', [
		'user_id'         => $this->quote->user_id,
	]);
	Event::assertDispatched(NotifyUser::class);
});


