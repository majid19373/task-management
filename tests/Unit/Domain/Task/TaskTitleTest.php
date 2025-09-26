<?php

namespace Tests\Unit\Domain\Task;

use DomainException;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Task\TaskTitle;

final class TaskTitleTest extends TestCase
{
    private const string EXCEPTION_MESSAGE = 'Task title must be between 5 and 100 characters.';

    #[Test]
    public function create_a_task_name_with_maximum_length()
    {
        // Arrange
        $value = Str::random(100);

        // Act
        $result = new TaskTitle($value);

        // Assert
        $this->assertEquals($value, $result->value());
        $this->assertEquals($value, (string)$result);
    }

    #[Test]
    public function create_a_task_name_with_minimum_length()
    {
        // Arrange
        $value = Str::random(5);

        // Act
        $result = new TaskTitle($value);

        // Assert
        $this->assertEquals($value, $result->value());
        $this->assertEquals($value, (string)$result);
    }

    #[Test]
    public function creating_a_task_name_when_value_length_be_too_short(): void
    {
        // Arrange
        $value = '';

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(self::EXCEPTION_MESSAGE);

        // Act
        new TaskTitle($value);
    }

    #[Test]
    public function creating_a_task_name_when_value_length_be_too_long(): void
    {
        // Arrange
        $value = Str::random(101);

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(self::EXCEPTION_MESSAGE);

        // Act
        new TaskTitle($value);
    }
}
