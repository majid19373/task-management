<?php

namespace Feature\Board;

use App\Http\Resources\Board\BoardResource;
use App\Models\Board;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class ShowBoardTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private const string BASE_ROUTE = 'api/v1/board';

    public function test_show_board(): void
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
