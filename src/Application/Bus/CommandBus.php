<?php

namespace Src\Application\Bus;

use Exception;
use Src\Application\CommandHandlers\CommandHandlerInterface;
use Src\Application\Commands\CommandInterface;

final readonly class CommandBus
{
    public function __construct(
        private CommandHandlerInterface $commandHandler,
    )
    {}

    /**
     * @throws Exception
     */
    public function dispatch(CommandInterface $command): void
    {
        $this->commandHandler->handle($command);
    }
}
