<?php

namespace Src\Application\Bus;

use Exception;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

final class CommandBus
{
    /** @var array<class-string, object> */
    private array $map = [];
    /**
     * @throws ReflectionException
     */
    public function __construct(iterable $commands)
    {
        foreach ($commands as $handler) {
            $queryClass = $this->resolveQueryClass($handler);
            $this->map[$queryClass] = $handler;
        }
    }

    /**
     * @throws Exception
     */
    public function dispatch(object $command): void
    {
        $commandClass = $command::class;

        if (!isset($this->map[$commandClass])) {
            throw new RuntimeException("No handler found for query {$commandClass}");
        }

        $this->map[$commandClass]->handle($command);
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
