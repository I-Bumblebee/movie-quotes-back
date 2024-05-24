<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$user = User::factory()->create([
			'name'     => 'admin',
			'email'    => 'admin@gmail.com',
			'password' => 'password',
		]);

		$movies = Movie::factory(10)->create(
			[
				'user_id' => $user->id,
			]
		);

		$quotes = $movies->flatMap(function ($movie) use ($user) {
			return $movie->quotes()->createMany(
				Quote::factory(1)->make(['user_id' => $user->id])->toArray()
			);
		});

		$otherUsers = User::factory(5)->create();

		$otherUsers->each(function ($otherUser) use ($quotes, $user) {
			$quotes->random(2)->each(function ($quote) use ($otherUser, $user) {
				$comment = Comment::factory()->create([
					'user_id'  => $otherUser->id,
					'quote_id' => $quote->id,
				]);
				$user->notifications()->create([
					'notifiable_type' => 'App\Models\Comment',
					'notifiable_id'   => $comment->id,
				]);
			});

			$quotes->random(2)->each(function ($quote) use ($otherUser, $user) {
				$like = $quote->likes()->create([
					'user_id' => $otherUser->id,
				]);

				$user->notifications()->create([
					'notifiable_type' => 'App\Models\Like',
					'notifiable_id'   => $like->id,
				]);
			});
		});

		collect(config('genres.genres'))
			->each(fn ($genre) => Genre::factory()->create($genre));

		$genres = Genre::all();

		$movies->each(function ($movie) use ($genres) {
			$movie->genres()->attach(
				$genres->random(rand(1, 3))->pluck('id')->toArray()
			);
		});
	}
}
