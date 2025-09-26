<?php

namespace Tests\Unit\Domain\Board;

use DomainException;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Board\BoardName;

final class BoardNameTest extends TestCase
{
    #[Test]
    public function creating_a_board_name_with_maximum_length(): void
    {
        // Arrange
        $value = Str::random(50);

        // Act
        $result = new BoardName($value);

        // Assert
        $this->assertEquals($value, $result->value());
        $this->assertEquals($value, (string)$result);
    }

    #[Test]
    public function creating_a_board_name_with_minimum_length(): void
    {
        // Arrange
        $value = Str::random(3);

        // Act
        $result = new BoardName($value);

        // Assert
        $this->assertEquals($value, $result->value());
        $this->assertEquals($value, (string)$result);
    }

    #[Test]
    public function creating_a_board_name_when_value_length_be_too_short(): void
    {
        // Arrange
        $value = '';

        // Expect
        $this->expectException(DomainException::class);

        // Act
        new BoardName($value);
    }

    #[Test]
    public function creating_a_board_name_when_value_length_be_too_long(): void
    {
        // Arrange
        $value = Str::random(51);

        // Expect
        $this->expectException(DomainException::class);

        // Act
        new BoardName($value);
    }
}
