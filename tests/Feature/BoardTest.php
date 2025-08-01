<?php

namespace Tests\Feature;

use App\Http\Resources\Board\BoardResource;
use App\Models\Board;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response as Res;

final class BoardTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private const string BASE_ROUTE = 'api/v1/board';

    public function test_list(): void
    {
        //Arrange
        Board::factory()->count(10)->create();
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertExactJsonStructure(
                parent::makePaginatorResponseStructure(BoardResource::JSON_STRUCTURE)
            );
    }

    public function test_list_without_pagination(): void
    {
        //Arrange
        Board::factory()->count(10)->create();
        $route = self::BASE_ROUTE . '?is_paginated=0';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertExactJsonStructure(
                parent::makeListMainJsonStructure(BoardResource::JSON_STRUCTURE)
            );
    }

    public function test_store(): void
    {
        //Arrange
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->id,
            'name' => 'Test Board',
            'description' => $this->faker->optional()->text(200),
        ];
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->postJson($route, $data, parent::BASE_HEADERS);

        //Assert
        $response->assertCreated()
            ->assertExactJsonStructure(
                parent::makeMainJsonStructureWithoutData()
            );
    }

    public function test_failed_store_with_same_name_same_user(): void
    {
        //Arrange
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->id,
            'name' => 'Test Board',
            'description' => $this->faker->optional()->text(200),
        ];
        $route = self::BASE_ROUTE;

        //Act
        $this->postJson($route, $data, parent::BASE_HEADERS);
        $response = $this->postJson($route, $data, parent::BASE_HEADERS);

        //Assert
        $response->assertServerError();
    }

    public function test_show(): void
    {
        //Arrange
        $board = Board::factory()->create();
        $route = self::BASE_ROUTE . "/{$board->id}";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertExactJsonStructure(
                parent::makeMainJsonStructure(BoardResource::JSON_STRUCTURE)
            );
    }
}
