<?php

namespace Feature\Subtask;

use App\Entities\Subtask;
use App\Entities\Task;
use App\Http\Resources\Subtask\SubtaskResource;
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
            'task_id' => $task->getId(),
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
