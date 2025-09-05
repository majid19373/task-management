<?php

namespace Src\Infrastructure\Persistence\Doctrine\Types\Board;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Src\Domain\Board\BoardName;
final class BoardNameType extends Type
{
    public const string NAME = 'board_name';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL([
            'length' => 50,
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?BoardName
    {
        return $value === null ? null : new BoardName($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof BoardName ? (string) $value : $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
