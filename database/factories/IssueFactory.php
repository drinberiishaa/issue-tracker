<?php

namespace Database\Factories;

use App\Models\Issue;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issue>
 */
class IssueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => fake()->sentence(6),
            'description' => fake()->paragraphs(2, true),
            'status' => fake()->randomElement(Issue::STATUSES),
            'priority' => fake()->randomElement(Issue::PRIORITIES),
            'due_date' => fake()->optional(0.7)->dateTimeBetween('now', '+2 months'),
        ];
    }

    public function open(): static
    {
        return $this->state(['status' => 'open']);
    }

    public function closed(): static
    {
        return $this->state(['status' => 'closed']);
    }

    public function highPriority(): static
    {
        return $this->state(['priority' => 'high']);
    }
}
