<?php

namespace Src\Application\Bus;

use Illuminate\Support\Facades\Cache;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

final class QueryBusFactory
{
    private string $cacheKey = 'query_bus';

    /**
     * @throws ReflectionException
     */
    public function make(iterable $handlers): void
    {
        $map = [];
        foreach ($handlers as $handler) {
            $queryClass = $this->resolveQueryClass($handler);
            $map[$queryClass] = get_class($handler);
        }
        Cache::put($this->cacheKey, $map);
    }

    public function getHandlers(): array
    {
        return Cache::get($this->cacheKey, []);
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
