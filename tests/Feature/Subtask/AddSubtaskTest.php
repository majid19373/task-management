<?php

namespace Feature\Subtask;

use App\Entities\Task;
use App\ValueObjects\Task\TaskStatus;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddSubtaskTest extends TestCase
{
    use WithFaker;
    private const string BASE_ROUTE = 'api/v1/task/';

    public function test_add_subtask(): void
    {
        //Arrange
        $task = entity(Task::class)->create();
        $data = [
            'task_id' => $task->getId(),
            'title' => 'Subtask Title',
            'description' => $this->faker->optional()->text(500),
        ];
        $route = self::BASE_ROUTE . "{$task->getId()}/subtask";

        //Act
        $response = $this->postJson($route, $data, parent::BASE_HEADERS);

        //Assert
        $response->assertCreated();
    }

    public function test_can_not_add_subtask_if_task_is_completed(): void
    {
        //Arrange
        $task = entity(Task::class)->create([
            'status' => TaskStatus::COMPLETED,
        ]);
        $data = [
            'task_id' => $task->getId(),
            'title' => 'Subtask Title',
            'description' => $this->faker->optional()->text(500),
        ];
        $route = self::BASE_ROUTE . "{$task->getId()}/subtask";

        //Act
        $response = $this->postJson($route, $data, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError();
    }
}
