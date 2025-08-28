<?php

namespace Feature\Board;

use App\Entities\Board;
use App\Http\Resources\Board\BoardResource;
use Tests\TestCase;

final class ListBoardTest extends TestCase
{
    private const string BASE_ROUTE = 'api/v1/board';

    public function test_list_board(): void
    {
        //Arrange
        entity(Board::class, 10)->create();
        $route = self::BASE_ROUTE . '?page=1';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertExactJsonStructure(
                parent::makePaginatorResponseStructure(BoardResource::JSON_STRUCTURE)
            );
    }

    public function test_list_board_without_pagination(): void
    {
        //Arrange
        entity(Board::class, 10)->create();
        $route = self::BASE_ROUTE;

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertExactJsonStructure(
                parent::makeListMainJsonStructure(BoardResource::JSON_STRUCTURE)
            );
    }
}
