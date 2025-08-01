<?php

namespace Feature\Task;

use App\Http\Resources\Task\TaskResource;
use App\Models\{Task};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListTaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private const string BASE_ROUTE = 'api/v1/task';

    public function setUpFaker(): void
    {
        Task::factory()->count(10)->create();
    }

    public function test_list_task(): void
    {
        //Arrange
        $route = self::BASE_ROUTE . '?board_id=1';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertExactJsonStructure(
                parent::makePaginatorResponseStructure(TaskResource::JSON_STRUCTURE)
            );
    }

    public function test_list_task_without_pagination(): void
    {
        //Arrange
        $route = self::BASE_ROUTE . '?board_id=1&is_paginated=0';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertExactJsonStructure(
                parent::makeListMainJsonStructure(TaskResource::JSON_STRUCTURE)
            );
    }

    public function test_list_task_error_without_board_id(): void
    {
        //Arrange
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertUnprocessable();
    }

    public function test_list_with_wrong_status(): void
    {
        //Arrange
        $route = self::BASE_ROUTE . '?board_id=1&status=test';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError();
    }

    public function test_list_task_with_wrong_priority(): void
    {
        //Arrange
        $route = self::BASE_ROUTE . '?board_id=1&priority=test';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError();
    }
}
