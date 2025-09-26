<?php

namespace Tests\Unit\Domain\Board;

use DomainException;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Board\BoardDescription;

final class BoardDescriptionTest extends TestCase
{
    private const string EXCEPTION_MESSAGE = 'Board description must be less than 200 characters.';

    #[Test]
    public function creating_a_board_description_with_maximum_length(): void
    {
        // Arrange
        $value = Str::random(200);

        // Act
        $result = new BoardDescription($value);

        // Assert
        $this->assertEquals($value, $result->value());
        $this->assertEquals($value, (string)$result);
    }

    #[Test]
    public function creating_a_board_description_when_length_be_too_long(): void
    {
        // Arrange
        $value = Str::random(201);

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(self::EXCEPTION_MESSAGE);

        // Act
        new BoardDescription($value);
    }
}
