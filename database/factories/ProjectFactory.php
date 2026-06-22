<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-3 months', 'now');

        return [
            'name' => fake()->unique()->catchPhrase(),
            'description' => fake()->paragraph(),
            'owner_id' => User::factory(),
            'start_date' => $start,
            'deadline' => fake()->dateTimeBetween($start, '+6 months'),
        ];
    }
}
