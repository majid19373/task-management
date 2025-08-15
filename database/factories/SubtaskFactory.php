<?php

namespace Database\Factories;

use App\Models\Subtask;
use App\Models\Task;
use App\ValueObjects\Subtask\SubtaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subtask>
 */
class SubtaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_id' => Task::factory()->create()->id,
            'title' => $this->faker->unique()->words(5, true),
            'description' => $this->faker->optional()->text(500),
            'status' => SubtaskStatus::NOT_STARTED,
        ];
    }
}
