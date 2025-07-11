<?php

namespace Tests\Feature;

use App\Http\Resources\Task\TaskEditResource;
use Carbon\Carbon;
use App\Enums\{TaskPriorityEnum, TaskStatusEnum};
use App\Http\Resources\Task\TaskResource;
use App\Models\{Task, Board};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response as Res;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private const BASE_ROUTE = 'api/v1/task';

    public function test_index(): void
    {
        //Arrange
        Task::factory()->count(10)->create();
        $route = self::BASE_ROUTE . '?board_id=1';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertStatus(Res::HTTP_OK);
        $response->assertExactJsonStructure(
            parent::makePaginatorResponseStructure(TaskResource::JSON_STRUCTURE)
        );
    }

    public function test_index_without_pagination(): void
    {
        //Arrange
        Task::factory()->count(10)->create();
        $route = self::BASE_ROUTE . '?board_id=1&is_paginated=0';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertStatus(Res::HTTP_OK);
        $response->assertExactJsonStructure(
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
        $response->assertStatus(Res::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_index_error_with_wrong_board_id(): void
    {
        //Arrange
        Task::factory()->count(10)->create();
        $route = self::BASE_ROUTE . '?board_id=10000';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertStatus(Res::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_store(): void
    {
        //Arrange
        $board = Board::factory()->create();
        $data = [
            'board_id' => $board->id,
            'title' => 'Board Title',
            'description' => $this->faker->optional()->text(500),
        ];
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->postJson($route, $data, parent::BASE_HEADERS);

        //Assert
        $response->assertStatus(Res::HTTP_CREATED);
        $response->assertExactJsonStructure(
            parent::makeMainJsonStructure(TaskResource::JSON_STRUCTURE)
        );
        $response->assertJsonFragment([
            'title' => $data['title'],
            'status' => TaskStatusEnum::NOT_STARTED,
            'priority' => TaskPriorityEnum::MEDIUM,
        ]);
    }

    public function test_failed_store_with_wrong_deadline(): void
    {
        //Arrange
        $board = Board::factory()->create();
        $data = [
            'board_id' => $board->id,
            'title' => $this->faker->unique()->words(5, true),
            'description' => $this->faker->optional()->text(500),
            'deadline' => $this->faker->dateTimeBetween('-2 years', '-1 day'),
        ];
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->postJson($route, $data, parent::BASE_HEADERS);

        //Assert
        $response->assertStatus(Res::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_show(): void
    {
        //Arrange
        $task = Task::factory()->create();
        $route = self::BASE_ROUTE . "/{$task->id}";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertStatus(Res::HTTP_OK);
        $response->assertExactJsonStructure(
            parent::makeMainJsonStructure(TaskResource::JSON_STRUCTURE)
        );
    }

    public function test_edit(): void
    {
        //Arrange
        $task = Task::factory()->create();
        $route = self::BASE_ROUTE . "/{$task->id}/edit";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertStatus(Res::HTTP_OK);
        $response->assertExactJsonStructure(
            parent::makeMainJsonStructure(TaskEditResource::JSON_STRUCTURE)
        );
    }

    public function test_start(): void
    {
        //Arrange
        $task = Task::factory()->create();
        $route = self::BASE_ROUTE . "/{$task->id}/start";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertStatus(Res::HTTP_OK);
        $response->assertExactJsonStructure(
            parent::makeMainJsonStructure(TaskResource::JSON_STRUCTURE)
        );
        $response->assertJsonFragment([
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
        $response->assertStatus(Res::HTTP_CONFLICT);
        $response->assertJsonFragment([
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
        $response->assertStatus(Res::HTTP_CONFLICT);
        $response->assertJsonFragment([
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
        $response->assertStatus(Res::HTTP_OK);
        $response->assertExactJsonStructure(
            parent::makeMainJsonStructure(TaskResource::JSON_STRUCTURE)
        );
        $response->assertJsonFragment([
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
        $response->assertStatus(Res::HTTP_CONFLICT);
        $response->assertJsonFragment([
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
        $response->assertStatus(Res::HTTP_CONFLICT);
        $response->assertJsonFragment([
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
        $response->assertStatus(Res::HTTP_OK);
        $response->assertExactJsonStructure(
            parent::makeMainJsonStructure(TaskResource::JSON_STRUCTURE)
        );
        $response->assertJsonFragment([
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
        $response->assertStatus(Res::HTTP_CONFLICT);
        $response->assertJsonFragment([
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
        $response->assertStatus(Res::HTTP_CONFLICT);
        $response->assertJsonFragment([
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
        $response->assertStatus(Res::HTTP_OK);
        $response->assertExactJsonStructure(
            parent::makeMainJsonStructure(TaskResource::JSON_STRUCTURE)
        );
        $response->assertJsonFragment([
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
        $response->assertStatus(Res::HTTP_UNPROCESSABLE_ENTITY);
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
        $response->assertStatus(Res::HTTP_CONFLICT);
        $response->assertJsonFragment([
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
            'deadline' => Carbon::now(),
        ];

        //Act
        $response = $this->post($route, $data,parent::BASE_HEADERS);

        //Assert
        $response->assertStatus(Res::HTTP_OK);
        $response->assertExactJsonStructure(
            parent::makeMainJsonStructure(TaskResource::JSON_STRUCTURE)
        );
        $response->assertJsonFragment([
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
        $response->assertStatus(Res::HTTP_UNPROCESSABLE_ENTITY);
    }
}
