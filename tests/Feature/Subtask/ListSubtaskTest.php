<?php

namespace Feature\Subtask;

use Src\Domain\Subtask\Subtask;
use Src\Domain\Task\Task;
use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Subtask\SubtaskResource;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListSubtaskTest extends TestCase
{
    use WithFaker;
    private const string BASE_ROUTE = 'api/v1/task/';

    public function test_list_subtask(): void
    {
        //Arrange
        $task = entity(Task::class)->create();
        entity(Subtask::class, 10)->create([
            'task' => $task,
        ]);
        $route = self::BASE_ROUTE . "{$task->getId()}/subtask";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertExactJsonStructure(
                parent::makeListMainJsonStructure(SubtaskResource::JSON_STRUCTURE)
            );
    }

    public function test_list_subtask_error_without_task_id(): void
    {
        //Arrange
        entity(Subtask::class, 10)->create();
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertUnprocessable();
    }
}
