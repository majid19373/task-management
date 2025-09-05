<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Controllers;

use Src\Application\Bus\{CommandBus, PaginateQueryBus, QueryBus};
use Src\Application\CommandHandlers\Board\CreateBoardCommandHandler;
use Src\Application\Queries\Board\GetBoardQuery;
use Src\Application\QueryHandlers\Board\{GetBoardQueryHandler, ListBoardQueryHandler, PaginatedListBoardQueryHandler};
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Common\Controller;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Board\{PaginateBoardFilterRequest, CreateBoardRequest};
use Illuminate\Http\JsonResponse;
use Exception;
use Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Board\BoardResource;

final class BoardController extends Controller
{
    public function list(PaginateBoardFilterRequest $request): JsonResponse
    {
        $query = $request->makeDTO();

        $bus = new QueryBus(resolve(ListBoardQueryHandler::class));
        $boards = $bus->ask($query);

        return $this->respond(
            data: BoardResource::toArrayList($boards),
        );
    }

    public function paginate(PaginateBoardFilterRequest $request): JsonResponse
    {
        $query = $request->makeDTO();

        $bus = new PaginateQueryBus(resolve(PaginatedListBoardQueryHandler::class));
        $boards = $bus->ask($query);

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

        $bus = new CommandBus(resolve(CreateBoardCommandHandler::class));
        $bus->dispatch($command);

        return $this->respondCreated();
    }

    public function show(int $boardId): JsonResponse
    {
        $query = new GetBoardQuery($boardId);

        $bus = new QueryBus(resolve(GetBoardQueryHandler::class));
        $board = $bus->ask($query);

        return $this->respond(
            data: BoardResource::toArray($board),
        );
    }
}
