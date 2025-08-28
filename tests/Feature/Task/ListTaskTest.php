<?php

namespace Feature\Task;

use Src\domain\Entities\Task\Task;
use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Task\TaskResource;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListTaskTest extends TestCase
{
    use WithFaker;
    private const string BASE_ROUTE = 'api/v1/task';

    public function test_list_task(): void
    {
        //Arrange
        entity(Task::class, 10)->create();
        $route = self::BASE_ROUTE . '?board_id=1&page=1';

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
        entity(Task::class, 10)->create();
        $route = self::BASE_ROUTE . '?board_id=1';

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
        entity(Task::class, 10)->create();
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertUnprocessable();
    }

    public function test_list_with_wrong_status(): void
    {
        //Arrange
        entity(Task::class, 10)->create();
        $route = self::BASE_ROUTE . '?board_id=1&status=test';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError();
    }

    public function test_list_task_with_wrong_priority(): void
    {
        //Arrange
        entity(Task::class, 10)->create();
        $route = self::BASE_ROUTE . '?board_id=1&priority=test';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError();
    }
}
