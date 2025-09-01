<?php

namespace Feature\Task;

use Src\Domain\Task\Task;
use Src\Domain\Task\TaskStatus;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StartTaskTest extends TestCase
{
    use WithFaker;
    private const string BASE_ROUTE = 'api/v1/task';

    public function test_start_task(): void
    {
        //Arrange
        $task = entity(Task::class)->create();
        $route = self::BASE_ROUTE . "/{$task->getId()}/start";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertJsonFragment([
                'message' => 'The task was started.',
            ]);
    }

    public function test_dont_be_start_task_with_in_progress_status(): void
    {
        //Arrange
        $task = entity(Task::class)->create([
            'status' => TaskStatus::IN_PROGRESS,
        ]);
        $route = self::BASE_ROUTE . "/{$task->getId()}/start";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task must not have started.',
            ]);
    }

    public function test_dont_be_start_task_with_completed_status(): void
    {
        //Arrange
        $task = entity(Task::class)->create([
            'status' => TaskStatus::COMPLETED,
        ]);
        $route = self::BASE_ROUTE . "/{$task->getId()}/start";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task must not have started.',
            ]);
    }
}
