<?php

namespace Feature\Task;

use App\Enums\{TaskPriorityEnum, TaskStatusEnum};
use App\Http\Resources\Task\TaskResource;
use App\Models\{Board, Task};
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReopenTaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private const string BASE_ROUTE = 'api/v1/task';

    public function test_reopen_task(): void
    {
        //Arrange
        $task = Task::factory()->create([
            'status' => TaskStatusEnum::COMPLETED->value,
        ]);
        $route = self::BASE_ROUTE . "/{$task->id}/reopen";

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
        $task = Task::factory()->create([
            'status' => TaskStatusEnum::NOT_STARTED->value,
        ]);
        $route = self::BASE_ROUTE . "/{$task->id}/reopen";

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
        $task = Task::factory()->create([
            'status' => TaskStatusEnum::IN_PROGRESS->value,
        ]);
        $route = self::BASE_ROUTE . "/{$task->id}/reopen";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task cannot reopened.',
            ]);
    }
}
