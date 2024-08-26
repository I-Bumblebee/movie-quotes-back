<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\User;
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
		return [
			'comment' => $this->faker->text,
			'user_id' => function () {
				return User::factory()->create()->id;
			},
			'quote_id' => function () {
				return Quote::factory()->create()->id;
			},
		];
	}
}
