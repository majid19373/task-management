<?php

namespace Feature\Task;

use App\Entities\Board;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddTaskTest extends TestCase
{
    use WithFaker;
    private const string BASE_ROUTE = 'api/v1/task';

    public function test_add_task(): void
    {
        //Arrange
        $board = entity(Board::class)->create();
        $data = [
            'board_id' => $board->getId(),
            'title' => 'Task Title',
            'description' => $this->faker->optional()->text(500),
        ];
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->postJson($route, $data, parent::BASE_HEADERS);

        //Assert
        $response->assertCreated();
    }

    public function test_failed_add_task_with_wrong_deadline(): void
    {
        //Arrange
        $board = entity(Board::class)->create();
        $data = [
            'board_id' => $board->getId(),
            'title' => $this->faker->unique()->words(5, true),
            'description' => $this->faker->optional()->text(500),
            'deadline' => $this->faker->dateTimeBetween('-2 years', '-1 day')->format('Y-m-d H:i:s'),
        ];
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->postJson($route, $data, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError();
    }
}
