<?php

namespace Database\Factories;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Genre>
 */
class GenreFactory extends Factory
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
            'name' => [
                'en' => $fakerEn->word(),
                'ka' => $fakerKa->word(),
            ],
		];
	}
}
