<?php

namespace Feature\Task;

use Src\domain\Entities\Task\Task;
use Src\domain\Entities\Task\ValueObjects\TaskStatus;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompletedTaskTest extends TestCase
{
    use WithFaker;
    private const string BASE_ROUTE = 'api/v1/task';

    public function test_completed_task(): void
    {
        //Arrange
        $task = entity(Task::class)->create([
            'status' => TaskStatus::IN_PROGRESS,
        ]);
        $route = self::BASE_ROUTE . "/{$task->getId()}/complete";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertJsonFragment([
                'message' => 'The task was completed.',
            ]);
    }

    public function test_dont_be_complete_task_with_not_started_status(): void
    {
        //Arrange
        $task = entity(Task::class)->create([
            'status' => TaskStatus::NOT_STARTED,
        ]);
        $route = self::BASE_ROUTE . "/{$task->getId()}/complete";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task must not have completed.',
            ]);
    }

    public function test_dont_be_complete_task_with_completed_status(): void
    {
        //Arrange
        $task = entity(Task::class)->create([
            'status' => TaskStatus::COMPLETED,
        ]);
        $route = self::BASE_ROUTE . "/{$task->getId()}/complete";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task must not have completed.',
            ]);
    }
}
