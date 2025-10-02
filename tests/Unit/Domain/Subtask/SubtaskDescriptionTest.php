<?php

namespace Tests\Unit\Domain\Subtask;

use DomainException;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Subtask\SubtaskDescription;

final class SubtaskDescriptionTest extends TestCase
{
    #[Test]
    public function create_a_subtask_description_with_maximum_length()
    {
        // Arrange
        $value = $this->maximumDescriptionLength();

        // Act
        $result = new SubtaskDescription($value);

        // Assert
        $this->assertEquals($value, $result->value());
        $this->assertEquals($value, (string)$result);
    }

    private function maximumDescriptionLength(): string
    {
        return Str::random(500);
    }

    #[Test]
    public function creating_a_subtask_description_when_value_length_be_too_long(): void
    {
        // Arrange
        $value = $this->tooLongDescriptionLength();

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Subtask description must be less than 500 characters.');

        // Act
        new SubtaskDescription($value);
    }

    private function tooLongDescriptionLength(): string
    {
        return Str::random(501);
    }
}
