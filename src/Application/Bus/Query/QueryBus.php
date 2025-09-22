<?php

namespace Src\Application\Bus\Query;

use RuntimeException;

abstract readonly class QueryBus implements QueryBusInterface
{
    public function __construct(private array $mapping)
    {}

    abstract public function resolveHandler(string $handler): object;

    public function ask(object $query): mixed
    {
        $queryClass = $query::class;

        if (!isset($this->mapping[$queryClass])) {
            throw new RuntimeException("No handler found for query {$queryClass}");
        }

        return $this->resolveHandler($this->mapping[$queryClass])->handle($query);
    }
}
