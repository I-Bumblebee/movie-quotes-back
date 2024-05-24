<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$fakerEn = FakerFactory::create();
		$fakerKa = FakerFactory::create('ka_GE');
		return [
			'quote'      => [
				'en' => $fakerEn->realText(100),
				'ka' => $fakerKa->realText(100),
			],
			'user_id'    => function () {
				return User::factory()->create()->id;
			},
			'movie_id'   => function () {
				return Movie::factory()->create()->id;
			},
		];
	}
}
