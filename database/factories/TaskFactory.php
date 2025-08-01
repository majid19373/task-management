<?php

namespace Database\Factories;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use App\Models\Board;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
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
            'status' => TaskStatusEnum::NOT_STARTED,
            'priority' => TaskPriorityEnum::MEDIUM,
            'deadline' => $this->faker->dateTimeBetween('+1 day', '+7 days'),
        ];
    }
}
