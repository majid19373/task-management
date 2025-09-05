<?php

namespace Src\Application\Bus;

use Exception;
use Src\Application\Queries\QueryInterface;
use Src\Application\QueryHandlers\QueryHandlerInterface;
use Src\Infrastructure\Persistence\Repositories\PaginatedResult;

final readonly class PaginateQueryBus
{
    public function __construct(
        private QueryHandlerInterface $commandHandler,
    )
    {}

    public function ask(QueryInterface $query): PaginatedResult
    {
        return $this->commandHandler->handle($query);
    }
}
