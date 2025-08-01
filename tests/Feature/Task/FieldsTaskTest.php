<?php

namespace Feature\Task;

use App\Enums\{TaskPriorityEnum, TaskStatusEnum};
use App\Http\Resources\Task\TaskResource;
use App\Models\{Board, Task};
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FieldsTaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private const string BASE_ROUTE = 'api/v1/task';

    public function test_status_priority_fields_task(): void
    {
        //Arrange
        $task = Task::factory()->create();
        $route = self::BASE_ROUTE . "/{$task->id}/status-priority-fields";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk();
    }
}
