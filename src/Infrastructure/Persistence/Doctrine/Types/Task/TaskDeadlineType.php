<?php

namespace Src\Infrastructure\Persistence\Doctrine\Types\Task;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Src\Domain\Task\TaskDeadline;
use DateTimeImmutable;

final class TaskDeadlineType extends Type
{
    public const string NAME = 'task_deadline';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getDateTypeDeclarationSQL([]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?TaskDeadline
    {
        return $value === null ? null : new TaskDeadline($value, new DateTimeImmutable());
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof TaskDeadline ? (string)$value : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
