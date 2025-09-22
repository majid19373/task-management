<?php

namespace Src\Application\Bus;

use ReflectionClass;
use ReflectionException;
use RuntimeException;

abstract readonly class MappingProvider implements MappingProviderInterface
{
    /**
     * @throws ReflectionException
     */
    public function mapping($handlers): array
    {
        $map = [];
        foreach ($handlers as $handler) {
            $queryClass = $this->resolveQueryClass($handler);
            $map[$queryClass] = get_class($handler);
        }
        return $map;
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
