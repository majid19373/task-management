<?php

namespace Tests\Feature;

use App\Http\Resources\Task\TaskEditResource;
use Carbon\Carbon;
use App\Enums\{TaskPriorityEnum, TaskStatusEnum};
use App\Http\Resources\Task\TaskResource;
use App\Models\{Task, Board};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private const string BASE_ROUTE = 'api/v1/task';

    public function test_list(): void
    {
        //Arrange
        Task::factory()->count(10)->create();
        $route = self::BASE_ROUTE . '?board_id=1';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertExactJsonStructure(
                parent::makePaginatorResponseStructure(TaskResource::JSON_STRUCTURE)
            );
    }

    public function test_list_without_pagination(): void
    {
        //Arrange
        Task::factory()->count(10)->create();
        $route = self::BASE_ROUTE . '?board_id=1&is_paginated=0';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertExactJsonStructure(
                parent::makeListMainJsonStructure(TaskResource::JSON_STRUCTURE)
            );
    }

    public function test_index_error_without_board_id(): void
    {
        //Arrange
        Task::factory()->count(10)->create();
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertUnprocessable();
    }

    public function test_list_with_wrong_status(): void
    {
        //Arrange
        Task::factory()->count(10)->create();
        $route = self::BASE_ROUTE . '?board_id=1&status=test';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError();
    }

    public function test_list_with_wrong_priority(): void
    {
        //Arrange
        Task::factory()->count(10)->create();
        $route = self::BASE_ROUTE . '?board_id=1&priority=test';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError();
    }

    public function test_create(): void
    {
        //Arrange
        $board = Board::factory()->create();
        $data = [
            'board_id' => $board->id,
            'title' => 'Task Title',
            'description' => $this->faker->optional()->text(500),
        ];
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->postJson($route, $data, parent::BASE_HEADERS);

        //Assert
        $response->assertCreated();
    }

    public function test_failed_create_with_wrong_deadline(): void
    {
        //Arrange
        $board = Board::factory()->create();
        $data = [
            'board_id' => $board->id,
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

//    public function test_create_with_parent(): void
//    {
//        //Arrange
//        $board = Board::factory()->create();
//        $task = Task::factory()->create();
//        $data = [
//            'board_id' => $board->id,
//            'parent_id' => $task->id,
//            'title' => 'Subtask Title',
//            'description' => $this->faker->optional()->text(500),
//        ];
//        $route = self::BASE_ROUTE;
//
//        //Act
//        $response = $this->postJson($route, $data, parent::BASE_HEADERS);
//
//        //Assert
//        $response->assertCreated();
//        $this->assertDatabaseHas('tasks', [
//            'parent_id' => $task->id,
//        ]);
//    }

    public function test_show(): void
    {
        //Arrange
        $task = Task::factory()->create();
        $route = self::BASE_ROUTE . "/{$task->id}";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk();
    }

    public function test_status_priority_fields(): void
    {
        //Arrange
        $task = Task::factory()->create();
        $route = self::BASE_ROUTE . "/{$task->id}/status-priority-fields";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk();
    }

    public function test_start(): void
    {
        //Arrange
        $task = Task::factory()->create();
        $route = self::BASE_ROUTE . "/{$task->id}/start";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertJsonFragment([
                'message' => 'The task was started.',
            ]);
    }

    public function test_dont_be_start_with_in_progress_status(): void
    {
        //Arrange
        $task = Task::factory()->create([
            'status' => TaskStatusEnum::IN_PROGRESS->value,
        ]);
        $route = self::BASE_ROUTE . "/{$task->id}/start";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task must not have started.',
            ]);
    }

    public function test_dont_be_start_with_completed_status(): void
    {
        //Arrange
        $task = Task::factory()->create([
            'status' => TaskStatusEnum::COMPLETED->value,
        ]);
        $route = self::BASE_ROUTE . "/{$task->id}/start";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task must not have started.',
            ]);
    }

    public function test_completed(): void
    {
        //Arrange
        $task = Task::factory()->create([
            'status' => TaskStatusEnum::IN_PROGRESS->value,
        ]);
        $route = self::BASE_ROUTE . "/{$task->id}/completed";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertJsonFragment([
                'message' => 'The task was completed.',
            ]);
    }

    public function test_dont_be_complete_with_not_started_status(): void
    {
        //Arrange
        $task = Task::factory()->create([
            'status' => TaskStatusEnum::NOT_STARTED->value,
        ]);
        $route = self::BASE_ROUTE . "/{$task->id}/completed";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task must not have completed.',
            ]);
    }

    public function test_dont_be_complete_with_completed_status(): void
    {
        //Arrange
        $task = Task::factory()->create([
            'status' => TaskStatusEnum::COMPLETED->value,
        ]);
        $route = self::BASE_ROUTE . "/{$task->id}/completed";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task must not have completed.',
            ]);
    }

    public function test_reopen(): void
    {
        //Arrange
        $task = Task::factory()->create([
            'status' => TaskStatusEnum::COMPLETED->value,
        ]);
        $route = self::BASE_ROUTE . "/{$task->id}/reopen";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertJsonFragment([
                'message' => 'The task was reopened.',
            ]);
    }

    public function test_dont_be_reopen_with_not_started_status(): void
    {
        //Arrange
        $task = Task::factory()->create([
            'status' => TaskStatusEnum::NOT_STARTED->value,
        ]);
        $route = self::BASE_ROUTE . "/{$task->id}/reopen";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task cannot reopened.',
            ]);
    }

    public function test_dont_be_reopen_with_in_progress_status(): void
    {
        //Arrange
        $task = Task::factory()->create([
            'status' => TaskStatusEnum::IN_PROGRESS->value,
        ]);
        $route = self::BASE_ROUTE . "/{$task->id}/reopen";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task cannot reopened.',
            ]);
    }

    public function test_priority(): void
    {
        //Arrange
        $task = Task::factory()->create();
        $route = self::BASE_ROUTE . "/priority";
        $data = [
            'id' => $task->id,
            'priority' => TaskPriorityEnum::LOW->value,
        ];

        //Act
        $response = $this->post($route, $data,parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertJsonFragment([
                'message' => 'The task was changed priority.',
            ]);
    }

    public function test_wrong_priority(): void
    {
        //Arrange
        $task = Task::factory()->create();
        $route = self::BASE_ROUTE . "/priority";
        $data = [
            'id' => $task->id,
            'priority' => 'test_priority',
        ];

        //Act
        $response = $this->post($route, $data,parent::BASE_HEADERS);

        //Assert
        $response->assertServerError();
    }

    public function test_dont_be_changed_priority_with_completed_status(): void
    {
        //Arrange
        $task = Task::factory()->create([
            'status' => TaskStatusEnum::COMPLETED->value,
        ]);
        $route = self::BASE_ROUTE . "/priority";
        $data = [
            'id' => $task->id,
            'priority' => TaskPriorityEnum::LOW->value,
        ];

        //Act
        $response = $this->post($route, $data,parent::BASE_HEADERS);

        //Assert
        $response->assertServerError()
            ->assertJsonFragment([
                'message' => 'The task cannot change the priority.',
            ]);
    }

    public function test_deadline(): void
    {
        //Arrange
        $task = Task::factory()->create();
        $route = self::BASE_ROUTE . "/deadline";
        $data = [
            'id' => $task->id,
            'deadline' => Carbon::tomorrow(),
        ];

        //Act
        $response = $this->post($route, $data,parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertJsonFragment([
                'message' => 'The task was changed deadline.',
            ]);
    }

    public function test_change_deadline_with_past_date(): void
    {
        //Arrange
        $task = Task::factory()->create();
        $route = self::BASE_ROUTE . "/deadline";
        $data = [
            'id' => $task->id,
            'deadline' => Carbon::now()->subDay(),
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
