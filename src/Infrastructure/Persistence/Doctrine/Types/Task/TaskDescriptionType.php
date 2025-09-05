<?php

namespace Src\Infrastructure\Persistence\Doctrine\Types\Task;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Src\Domain\Task\TaskDescription;

final class TaskDescriptionType extends Type
{
    public const string NAME = 'task_description';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL([
            'length' => 500,
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?TaskDescription
    {
        return $value === null ? null : new TaskDescription($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof TaskDescription ? (string)$value : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
