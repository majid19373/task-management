<?php

namespace Feature\Task;

use App\ValueObjects\Task\TaskPriority;
use App\ValueObjects\Task\TaskStatus;
use App\Models\{Task};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PriorityTaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private const string BASE_ROUTE = 'api/v1/task';

    public function test_change_priority_task(): void
    {
        //Arrange
        $task = Task::factory()->create();
        $route = self::BASE_ROUTE . "/priority";
        $data = [
            'id' => $task->id,
            'priority' => TaskPriority::LOW->value,
        ];

        //Act
        $response = $this->post($route, $data,parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertJsonFragment([
                'message' => 'The task was changed priority.',
            ]);
    }

    public function test_wrong_change_priority_task(): void
    {
        //Arrange
        $task = Task::factory()->create();
        $route = self::BASE_ROUTE . "/priority";
        $data = [
            'id' => $task->id,
            'priority' => 'test_priority',
        ];

        //Act
        $response = $this->post($route, $data,parent::BASE_HEADERS);

        //Assert
        $response->assertServerError();
    }

    public function test_dont_be_changed_priority_task_with_completed_status(): void
    {
        //Arrange
        $task = Task::factory()->create([
            'status' => TaskStatus::COMPLETED->value,
        ]);
        $route = self::BASE_ROUTE . "/priority";
        $data = [
            'id' => $task->id,
            'priority' => TaskPriority::LOW->value,
        ];

        //Act
        $response = $this->post($route, $data,parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task cannot change the priority.',
            ]);
    }
}
