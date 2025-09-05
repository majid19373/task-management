<?php

namespace Src\Application\QueryHandlers;

use Src\Application\Queries\QueryInterface;

interface QueryHandlerInterface
{
    public function handle(QueryInterface $query): mixed;
}
