<?php

namespace Src\Application\Contracts;

use Src\Application\Contracts\CommandInterface;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command): void;
}
