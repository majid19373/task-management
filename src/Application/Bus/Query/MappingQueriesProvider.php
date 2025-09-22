<?php

namespace Src\Application\Bus\Query;

use ReflectionException;
use Src\Application\Bus\MappingProvider;
use Src\Application\Bus\MappingProviderInterface;

final readonly class MappingQueriesProvider extends MappingProvider implements MappingProviderInterface
{
    /**
     * @throws ReflectionException
     */
    public function provide(iterable $handlers): array
    {
        return $this->mapping($handlers);
    }
}
