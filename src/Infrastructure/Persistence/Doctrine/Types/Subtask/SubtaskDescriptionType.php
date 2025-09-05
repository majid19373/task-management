<?php

namespace Src\Infrastructure\Persistence\Doctrine\Types\Subtask;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Src\Domain\Subtask\SubtaskDescription;

final class SubtaskDescriptionType extends Type
{
    public const string NAME = 'subtask_description';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL([
            'length' => 500,
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?SubtaskDescription
    {
        return $value === null ? null : new SubtaskDescription($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof SubtaskDescription ? (string)$value : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
