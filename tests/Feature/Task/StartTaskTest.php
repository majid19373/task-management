<?php

namespace Feature\Task;

use App\ValueObjects\Task\TaskStatus;
use App\Models\{Task};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StartTaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private const string BASE_ROUTE = 'api/v1/task';

    public function test_start_task(): void
    {
        //Arrange
        $task = Task::factory()->create();
        $route = self::BASE_ROUTE . "/{$task->id}/start";

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
        $task = Task::factory()->create([
            'status' => TaskStatus::IN_PROGRESS->value,
        ]);
        $route = self::BASE_ROUTE . "/{$task->id}/start";

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
        $task = Task::factory()->create([
            'status' => TaskStatus::COMPLETED->value,
        ]);
        $route = self::BASE_ROUTE . "/{$task->id}/start";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task must not have started.',
            ]);
    }
}
