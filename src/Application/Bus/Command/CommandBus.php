<?php

namespace Src\Application\Bus\Command;

use Exception;
use RuntimeException;

abstract readonly class CommandBus implements CommandBusInterface
{
    public function __construct(private array $mapping)
    {}

    abstract public function resolveHandler(string $handler): object;

    /**
     * @throws Exception
     */
    public function dispatch(object $command): void
    {
        $commandClass = $command::class;

        if (!isset($this->mapping[$commandClass])) {
            throw new RuntimeException("No handler found for query {$commandClass}");
        }

        $this->resolveHandler($this->mapping[$commandClass])->handle($command);
    }

}
