<?php

namespace Feature\Subtask;

use App\Models\Board;
use App\Models\Task;
use App\ValueObjects\Task\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddSubtaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private const string BASE_ROUTE = 'api/v1/subtask';

    public function test_add_subtask(): void
    {
        //Arrange
        $task = Task::factory()->create();
        $data = [
            'task_id' => $task->id,
            'title' => 'Subtask Title',
            'description' => $this->faker->optional()->text(500),
        ];
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->postJson($route, $data, parent::BASE_HEADERS);

        //Assert
        $response->assertCreated();
        $this->assertDatabaseHas('tasks', [
            'task_id' => $task->id,
        ]);
    }

    public function test_can_not_add_subtask_if_task_is_completed(): void
    {
        //Arrange
        $task = Task::factory()->create([
            'status' => TaskStatus::COMPLETED->value,
        ]);
        $data = [
            'task_id' => $task->id,
            'title' => 'Subtask Title',
            'description' => $this->faker->optional()->text(500),
        ];
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->postJson($route, $data, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError();
    }
}
