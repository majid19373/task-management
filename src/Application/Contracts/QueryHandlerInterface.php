<?php

namespace Src\Application\Contracts;

/**
 * @template T of QueryInterface
 */
interface QueryHandlerInterface
{
    /**
     * @param QueryInterface $query
     * @return mixed
     */
    public function handle(QueryInterface $query): mixed;
}
