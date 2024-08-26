<?php

namespace Database\Factories;

use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
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
			'title'         => [
				'en' => $fakerEn->realText(20),
				'ka' => $fakerKa->realText(20),
			],
			'description'   => [
				'en' => $fakerEn->realText(400),
				'ka' => $fakerKa->realText(400),
			],
			'release_year'   => $fakerEn->year(),
			'director_name'  => [
				'en' => $fakerEn->name(),
				'ka' => $fakerKa->name(),
			],
			'user_id'        => function () {
				return User::factory()->create()->id;
			},
		];
	}
}
