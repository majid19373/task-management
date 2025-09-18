<?php

namespace Src\Application\Bus;

use Exception;
use ReflectionException;
use RuntimeException;

final class CommandBus
{
    /** @var array<class-string, object> */
    private array $map = [];
    /**
     * @throws ReflectionException
     */
    public function __construct(CommandBusFactory $busFactory, iterable $commands)
    {
        $this->map = $busFactory->getHandlers();
        if(!count($this->map)){
            $busFactory->make($commands);
            $this->map = $busFactory->getHandlers();
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
}
