<?php

namespace Src\Application\Bus\Command;

interface CommandBusInterface
{
    public function dispatch(object $command): void;
}
