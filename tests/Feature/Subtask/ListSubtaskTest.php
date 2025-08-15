<?php

namespace Feature\Subtask;

use App\Http\Resources\Subtask\SubtaskResource;
use App\Models\{Subtask, Task};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListSubtaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private const string BASE_ROUTE = 'api/v1/subtask';

    public function setUpFaker(): void
    {
        $task = Task::factory()->create();
        Subtask::factory()->count(10)->create([
            'task_id' => $task->id,
        ]);
    }

    public function test_list_subtask(): void
    {
        //Arrange
        $route = self::BASE_ROUTE . '?task_id=1';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertExactJsonStructure(
                parent::makePaginatorResponseStructure(SubtaskResource::JSON_STRUCTURE)
            );
    }

    public function test_list_subtask_without_pagination(): void
    {
        //Arrange
        $route = self::BASE_ROUTE . '?task_id=1&is_paginated=0';

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
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertUnprocessable();
    }
}
