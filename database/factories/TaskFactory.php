<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\Task;
use App\ValueObjects\Task\TaskStatus;
use App\ValueObjects\Task\TaskPriority;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'board_id' => Board::factory(),
            'task_id' => null,
            'title' => $this->faker->unique()->words(5, true),
            'description' => $this->faker->optional()->text(500),
            'status' => TaskStatus::NOT_STARTED,
            'priority' => TaskPriority::MEDIUM,
            'deadline' => $this->faker->dateTimeBetween('+1 day', '+7 days'),
        ];
    }
}
