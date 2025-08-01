<?php

namespace Feature\Board;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class CreateBoardTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private const string BASE_ROUTE = 'api/v1/board';

    public function test_create_board(): void
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

    public function test_failed_create_board_with_same_name_same_user(): void
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
}
