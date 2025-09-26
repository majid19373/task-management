<?php

namespace Tests\Unit\Domain\Task;

use DomainException;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Task\TaskDescription;

final class TaskDescriptionTest extends TestCase
{
    private const string EXCEPTION_MESSAGE = 'Task description must be less than 500 characters.';

    #[Test]
    public function create_a_task_description_with_maximum_length()
    {
        // Arrange
        $value = Str::random(500);

        // Act
        $result = new TaskDescription($value);

        // Assert
        $this->assertEquals($value, $result->value());
        $this->assertEquals($value, (string)$result);
    }

    #[Test]
    public function creating_a_task_description_when_value_length_be_too_long(): void
    {
        // Arrange
        $value = Str::random(501);

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(self::EXCEPTION_MESSAGE);

        // Act
        new TaskDescription($value);
    }
}
