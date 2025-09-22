<?php

namespace Src\Application\Bus\Command;

use ReflectionException;
use Src\Application\Bus\MappingProvider;
use Src\Application\Bus\MappingProviderInterface;

final readonly class MappingCommandsProvider extends MappingProvider implements MappingProviderInterface
{
    /**
     * @throws ReflectionException
     */
    public function provide(iterable $handlers): array
    {
        return $this->mapping($handlers);
    }
}
