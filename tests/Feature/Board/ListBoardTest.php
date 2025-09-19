<?php

namespace Feature\Board;

use Src\Domain\Board\Board;
use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Board\BoardResource;
use Tests\TestCase;

final class ListBoardTest extends TestCase
{
    private const string BASE_ROUTE = 'api/v1/board';

    public function test_paginate_board(): void
    {
        //Arrange
        entity(Board::class, 10)->create();
        $route = self::BASE_ROUTE . '/paginate?user_id=1';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertExactJsonStructure(
                parent::makePaginatorResponseStructure(BoardResource::JSON_STRUCTURE)
            );
    }

    public function test_list_board(): void
    {
        //Arrange
        entity(Board::class, 10)->create();
        $route = self::BASE_ROUTE . '?user_id=1';

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertExactJsonStructure(
                parent::makeListMainJsonStructure(BoardResource::JSON_STRUCTURE)
            );
    }
}
