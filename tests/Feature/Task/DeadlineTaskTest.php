<?php

namespace Feature\Task;

use App\Entities\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeadlineTaskTest extends TestCase
{
    use WithFaker;
    private const string BASE_ROUTE = 'api/v1/task';

    public function test_change_deadline_task(): void
    {
        //Arrange
        $task = entity(Task::class)->create();
        $route = self::BASE_ROUTE . "/deadline";
        $data = [
            'id' => $task->getId(),
            'deadline' => Carbon::tomorrow()->format('Y-m-d H:i:s'),
        ];

        //Act
        $response = $this->post($route, $data,parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertJsonFragment([
                'message' => 'The task was changed deadline.',
            ]);
    }

    public function test_change_deadline_task_with_past_date(): void
    {
        //Arrange
        $task = entity(Task::class)->create();
        $route = self::BASE_ROUTE . "/deadline";
        $data = [
            'id' => $task->getId(),
            'deadline' => Carbon::now()->subDay()->toString(),
        ];

        //Act
        $response = $this->post($route, $data,parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The deadline field must be a valid date',
            ]);
    }
}
