<?php

namespace Src\Application\Bus\Query;

use Illuminate\Support\Facades\Cache;
use ReflectionException;
use Src\Application\Bus\MappingProvider;
use Src\Application\Bus\MappingProviderInterface;

final readonly class CachingMappingQueriesProvider extends MappingProvider implements MappingProviderInterface
{
    private const string CACHE_KEY = 'query_bus';

    /**
     * @throws ReflectionException
     */
    public function provide(iterable $handlers): array
    {
        $map = Cache::get(self::CACHE_KEY, []);

        if(count($map) === 0){
            $map = $this->mapping($handlers);
            Cache::put(self::CACHE_KEY, $map);
        }

        return $map;
    }
}
