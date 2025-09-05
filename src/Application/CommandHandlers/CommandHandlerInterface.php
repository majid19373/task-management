<?php

namespace Src\Application\CommandHandlers;

use Src\Application\Commands\CommandInterface;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command): void;
}
