<?php

namespace Tests\Unit\Domain\Board;

use DomainException;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Board\BoardName;

final class BoardNameTest extends TestCase
{
    public static function provideValidNameLengths(): array
    {
        return [
            'MAX' => [50],
            'MIN' => [3],
        ];
    }
    public static function provideInvalidNameLengths(): array
    {
        return [
            'TOO_SHORT' => [0],
            'TOO_LONG' => [51],
        ];
    }

    #[Test]
    #[DataProvider('provideValidNameLengths')]
    public function creating_a_board_name(int $length): void
    {
        // Arrange
        $value = Str::random($length);

        // Act
        $result = new BoardName($value);

        // Assert
        $this->assertEquals($value, $result->value());
        $this->assertEquals($value, (string)$result);
    }

    #[Test]
    #[DataProvider('provideInvalidNameLengths')]
    public function creating_a_board_name_when_value_length_be_wrong(int $length): void
    {
        // Arrange
        $value = Str::random($length);

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Board name must be between 3 and 50 characters.');

        // Act
        new BoardName($value);
    }
}
