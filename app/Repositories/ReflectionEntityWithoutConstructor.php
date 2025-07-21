<?php

namespace App\Repositories;

use ReflectionClass;
use ReflectionException;

final class ReflectionEntityWithoutConstructor
{
    private ReflectionClass $reflection;
    private object $entity;

    /**
     * @throws ReflectionException
     */
    public function __construct($class)
    {
        $this->reflection = new ReflectionClass($class);
        $this->entity = $this->reflection->newInstanceWithoutConstructor();
    }

    /**
     * @throws ReflectionException
     */
    public function setValueInProperty(string $key, mixed $value = null): void
    {
        $property = $this->reflection->getProperty($key);
        $property->setValue($this->entity, $value);
    }

    public function getEntity(): object
    {
        return $this->entity;
    }
}
