<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Controllers;

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
    public function __construct(
        private readonly ListBoardQueryHandler $listBoardQueryHandler,
        private readonly PaginatedListBoardQueryHandler $paginatedListBoardQueryHandler,
        private readonly CreateBoardCommandHandler $createBoardCommandHandler,
        private readonly GetBoardQueryHandler $getBoardQueryHandler,
    )
    {}

    public function list(): JsonResponse
    {
        $boards = $this->listBoardQueryHandler->handle();
        return $this->respond(
            data: BoardResource::toArrayList($boards),
        );
    }

    public function paginate(PaginateBoardFilterRequest $request): JsonResponse
    {
        $query = $request->makeDTO();
        $boards = $this->paginatedListBoardQueryHandler->handle($query);
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
        $this->createBoardCommandHandler->handle($command);
        return $this->respondCreated();
    }

    /**
     * @throws Exception
     */
    public function show(int $boardId): JsonResponse
    {
        $query = new GetBoardQuery($boardId);
        $board = $this->getBoardQueryHandler->handle($query);
        return $this->respond(
            data: BoardResource::toArray($board),
        );
    }
}
