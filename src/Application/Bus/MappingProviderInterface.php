<?php

namespace Src\Application\Bus;

interface MappingProviderInterface
{
    public function provide(array $handlers): array;
}
