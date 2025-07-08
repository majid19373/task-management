<?php

namespace App\DTO;

use Illuminate\Support\Arr;
use ReflectionClass;

abstract class BaseDTO
{
    protected const NOT_PERSISTED = '_not_persisted_';

    public static function make(array $args): static
    {
        $constructorParams = (new ReflectionClass(static::class))
            ->getConstructor()
            ->getParameters();

        $allowedKeys = array_map(fn($param) => $param->getName(), $constructorParams);

        $filteredArgs = array_filter(
            $args,
            fn($key) => in_array($key, $allowedKeys, true),
            ARRAY_FILTER_USE_KEY
        );

        return new static(...$filteredArgs);
    }

    public function toArray(): array
    {
        return Arr::except(
            array_filter(get_object_vars($this), fn($value) => $value !== self::NOT_PERSISTED),
            array_keys(get_class_vars(self::class))
        );
    }

}
