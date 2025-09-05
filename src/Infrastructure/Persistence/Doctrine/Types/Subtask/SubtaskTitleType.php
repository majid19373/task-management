<?php

namespace Src\Infrastructure\Persistence\Doctrine\Types\Subtask;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Src\Domain\Subtask\SubtaskTitle;

final class SubtaskTitleType extends Type
{
    public const string NAME = 'subtask_title';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL([
            'length' => 100,
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?SubtaskTitle
    {
        return $value === null ? null : new SubtaskTitle($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof SubtaskTitle ? (string)$value : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
