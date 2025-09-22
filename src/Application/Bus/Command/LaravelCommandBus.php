<?php

namespace Src\Application\Bus\Command;

final readonly class LaravelCommandBus extends CommandBus
{
    public function resolveHandler(string $handler): object
    {
        return app($handler);
    }
}
