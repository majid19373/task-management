<?php

namespace Feature\Board;

use App\Http\Resources\Board\BoardResource;
use Database\Factories\BoardFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class ListBoardTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    private const string BASE_ROUTE = 'api/v1/board';

    public function test_list_board(): void
    {
        //Arrange
        BoardFactory::createWithCount($this->em, 10);
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
        BoardFactory::createWithCount($this->em, 10);
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
