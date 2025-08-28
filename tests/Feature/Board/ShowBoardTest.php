<?php

namespace Feature\Board;

use Src\domain\Entities\Board\Board;
use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Board\BoardResource;
use Tests\TestCase;

final class ShowBoardTest extends TestCase
{
    private const string BASE_ROUTE = 'api/v1/board';

    public function test_show_board(): void
    {
        //Arrange
        $board = entity(Board::class)->create();
        $route = self::BASE_ROUTE . "/{$board->getId()}";

        //Act
        $response = $this->get($route, parent::BASE_HEADERS);

        //Assert
        $response->assertOk()
            ->assertExactJsonStructure(
                parent::makeMainJsonStructure(BoardResource::JSON_STRUCTURE)
            );
    }
}
