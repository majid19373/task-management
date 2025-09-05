<?php

namespace Src\Infrastructure\Persistence\Doctrine\Types\Board;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Src\Domain\Board\BoardDescription;
final class BoardDescriptionType extends Type
{
    public const string NAME = 'board_description';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL([
            'length' => 200,
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?BoardDescription
    {
        return $value === null ? null : new BoardDescription($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof BoardDescription ? (string) $value : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
