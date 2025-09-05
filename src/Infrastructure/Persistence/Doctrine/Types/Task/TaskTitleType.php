<?php

namespace Src\Infrastructure\Persistence\Doctrine\Types\Task;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Src\Domain\Task\TaskTitle;

final class TaskTitleType extends Type
{
    public const string NAME = 'task_title';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL([
            'length' => 100,
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?TaskTitle
    {
        return $value === null ? null : new TaskTitle($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof TaskTitle ? (string)$value : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
