<?php

namespace Src\Application\Bus;

use ReflectionException;
use RuntimeException;

final class QueryBus
{
    /** @var array<class-string, object> */
    private array $map = [];

    /**
     * @throws ReflectionException
     */
    public function __construct(QueryBusFactory $busFactory, iterable $queries)
    {
        $this->map = $busFactory->getHandlers();
        if(!count($this->map)){
            $busFactory->make($queries);
            $this->map = $busFactory->getHandlers();
        }
    }

    public function ask(object $query): mixed
    {
        $queryClass = $query::class;

        if (!isset($this->map[$queryClass])) {
            throw new RuntimeException("No handler found for query {$queryClass}");
        }

        return $this->map[$queryClass]->handle($query);
    }
}
