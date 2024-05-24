<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
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
            'comment' => [
                'en' => $fakerEn->realText(100),
                'ka' => $fakerKa->realText(100),
            ],
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'quote_id' => function () {
                return Quote::factory()->create()->id;
            },
		];
	}
}
