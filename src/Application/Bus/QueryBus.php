<?php

namespace Src\Application\Bus;

use ReflectionClass;
use ReflectionException;
use RuntimeException;

final class QueryBus
{
    /** @var array<class-string, object> */
    private array $map = [];

    /**
     * @throws ReflectionException
     */
    public function __construct(iterable $handlers)
    {
        foreach ($handlers as $handler) {
            $queryClass = $this->resolveQueryClass($handler);
            $this->map[$queryClass] = $handler;
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

    /**
     * @throws ReflectionException
     */
    private function resolveQueryClass(object $handler): string
    {
        $method = new ReflectionClass($handler)->getMethod('handle');
        $param = $method->getParameters()[0] ?? null;

        $type = $param?->getType();

        if (!$type || $type->isBuiltin()) {
            throw new RuntimeException(
                "Handler ".get_class($handler)." must type-hint a Query class."
            );
        }

        return $type->getName();
    }
}
