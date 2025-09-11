<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Controllers;

use Src\Application\Bus\{CommandBus, QueryBus};
use Src\Application\Queries\Board\GetBoardQuery;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Common\Controller;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Board\{BoardListRequest,
    PaginateBoardFilterRequest,
    CreateBoardRequest};
use Illuminate\Http\JsonResponse;
use Exception;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Board\BoardResource;

final class BoardController extends Controller
{
    public function __construct(
        private readonly QueryBus   $queryBus,
        private readonly CommandBus $commandBus,
    )
    {}

    public function list(BoardListRequest $request): JsonResponse
    {
        $query = $request->makeDTO();

        $boards = $this->queryBus->ask($query);

        return $this->respond(
            data: BoardResource::toArrayList($boards),
        );
    }

    public function paginate(PaginateBoardFilterRequest $request): JsonResponse
    {
        $query = $request->makeDTO();

        $boards = $this->queryBus->ask($query);

        return $this->respondWithPagination(
            paginate: $boards->paginator,
            data: BoardResource::toArrayList($boards->list),
        );
    }

    /**
     * @throws Exception
     */
    public function create(CreateBoardRequest $request): JsonResponse
    {
        $command = $request->makeDTO();

        $this->commandBus->dispatch($command);

        return $this->respondCreated();
    }

    public function show(int $boardId): JsonResponse
    {
        $query = new GetBoardQuery($boardId);

        $board = $this->queryBus->ask($query);

        return $this->respond(
            data: BoardResource::toArray($board),
        );
    }
}
