<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (rand(0, 1) === 0) {
            $notifiable = Comment::factory()->create();
        } else {
            $notifiable = Like::factory()->create();
        }

        return [
            'is_read' => $this->faker->boolean,
            'notifiable_id' => $notifiable->id,
            'notifiable_type' => get_class($notifiable),
            'user_id' => function () {
                return User::factory()->create()->id;
            },
        ];
    }
}
