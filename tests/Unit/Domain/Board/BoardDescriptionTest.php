<?php

namespace Tests\Unit\Domain\Board;

use DomainException;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Board\BoardDescription;

final class BoardDescriptionTest extends TestCase
{
    #[Test]
    public function creating_a_board_description_with_maximum_length(): void
    {
        // Arrange
        $value = $this->maximumDescriptionLength();

        // Act
        $result = new BoardDescription($value);

        // Assert
        $this->assertEquals($value, $result->value());
        $this->assertEquals($value, (string)$result);
    }

    private function maximumDescriptionLength(): string
    {
        return Str::random(200);
    }

    #[Test]
    public function creating_a_board_description_when_length_be_too_long(): void
    {
        // Arrange
        $value = $this->tooLongDescriptionLength();

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Board description must be less than 200 characters.');

        // Act
        new BoardDescription($value);
    }

    private function tooLongDescriptionLength(): string
    {
        return Str::random(201);
    }
}
