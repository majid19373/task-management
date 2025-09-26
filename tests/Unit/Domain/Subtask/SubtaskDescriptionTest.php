<?php

namespace Tests\Unit\Domain\Subtask;

use DomainException;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Subtask\SubtaskDescription;

final class SubtaskDescriptionTest extends TestCase
{
    private const string EXCEPTION_MESSAGE = 'Subtask description must be less than 500 characters.';

    #[Test]
    public function create_a_subtask_description_with_maximum_length()
    {
        // Arrange
        $value = Str::random(500);

        // Act
        $result = new SubtaskDescription($value);

        // Assert
        $this->assertEquals($value, $result->value());
        $this->assertEquals($value, (string)$result);
    }

    #[Test]
    public function creating_a_subtask_description_when_value_length_be_too_long(): void
    {
        // Arrange
        $value = Str::random(501);

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(self::EXCEPTION_MESSAGE);

        // Act
        new SubtaskDescription($value);
    }
}
