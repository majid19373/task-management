<?php

namespace Feature\Task;

use Src\Domain\Task\Task;
use Src\Domain\Task\TaskStatus;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReopenTaskTest extends TestCase
{
    use WithFaker;
    private const string BASE_ROUTE = 'api/v1/task';

    public function test_reopen_task(): void
    {
        //Arrange
        $task = entity(Task::class)->create([
            'status' => TaskStatus::COMPLETED,
        ]);
        $route = self::BASE_ROUTE . "/{$task->getId()}/reopen";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertJsonFragment([
                'message' => 'The task was reopened.',
            ]);
    }

    public function test_dont_be_reopen_task_with_not_started_status(): void
    {
        //Arrange
        $task = entity(Task::class)->create([
            'status' => TaskStatus::NOT_STARTED,
        ]);
        $route = self::BASE_ROUTE . "/{$task->getId()}/reopen";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task cannot reopened.',
            ]);
    }

    public function test_dont_be_reopen_task_with_in_progress_status(): void
    {
        //Arrange
        $task = entity(Task::class)->create([
            'status' => TaskStatus::IN_PROGRESS,
        ]);
        $route = self::BASE_ROUTE . "/{$task->getId()}/reopen";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task cannot reopened.',
            ]);
    }
}
