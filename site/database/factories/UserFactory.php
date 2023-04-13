<?php

namespace Database\Factories;

use DateTime;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
            'password' => $this->faker->text(),
            'expires_at' => '2023-12-12 04:26:03',
            'email_verified_at' => now(),
            // 'remember_token' => Str::random(10),
            'role' => 'shopper',
            'avatar'=> 'images/profiles/default-avatar.jpg'
        ];

        // return [
        //     'name' => $this->faker->name(),
        //     'email' => $this->faker->unique()->safeEmail(),
        //     'email_verified_at' => now(),
        //     'password' => $this->faker->text(), // password
        //     // 'remember_token' => Str::random(10),
        //     // 'api_token' => fake()->unique(),
        // ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
