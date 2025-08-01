<?php

namespace Feature\Subtask;

use App\Models\Board;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddSubtaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private const string BASE_ROUTE = 'api/v1/subtask';

    public function test_add_subtask(): void
    {
        //Arrange
        $board = Board::factory()->create();
        $task = Task::factory()->create();
        $data = [
            'board_id' => $board->id,
            'task_id' => $task->id,
            'title' => 'Subtask Title',
            'description' => $this->faker->optional()->text(500),
        ];
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->postJson($route, $data, parent::BASE_HEADERS);

        //Assert
        $response->assertCreated();
        $this->assertDatabaseHas('tasks', [
            'task_id' => $task->id,
        ]);
    }
}
