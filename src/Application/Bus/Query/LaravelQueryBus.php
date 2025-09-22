<?php

namespace Src\Application\Bus\Query;

final readonly class LaravelQueryBus extends QueryBus
{
    public function resolveHandler(string $handler): object
    {
        return app($handler);
    }
}
