<?php

namespace Src\Application\Bus;

use Src\Application\Queries\QueryInterface;
use Src\Application\QueryHandlers\QueryHandlerInterface;

final readonly class QueryBus
{
    public function __construct(
        private QueryHandlerInterface $commandHandler,
    )
    {}

    public function ask(QueryInterface $query): mixed
    {
        return $this->commandHandler->handle($query);
    }
}
