<?php

namespace Feature\Task;

use App\Entities\Task;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowTaskTest extends TestCase
{
    use WithFaker;
    private const string BASE_ROUTE = 'api/v1/task';

    public function test_show_task(): void
    {
        //Arrange
        $task = entity(Task::class)->create();
        $route = self::BASE_ROUTE . "/{$task->getId()}";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk();
    }
}
