<?php

namespace Tests\Unit\Domain\Task;

use DomainException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Task\TaskDeadline;
use DateTimeImmutable;

final class TaskDeadlineTest extends TestCase
{
    private const string WRONG_VALUE_EXCEPTION_MESSAGE = 'The deadline field must be a valid date.';
    private const string DEADLINE_MUST_BE_FUTURE_MESSAGE = 'The deadline date must be greater than the current date.';

    #[Test]
    public function create_a_task_deadline()
    {
        // Arrange
        $value = now()->addMinute()->format('Y-m-d H:i:s');

        // Act
        $result = new TaskDeadline($value, new DateTimeImmutable());

        // Assert
        $this->assertInstanceOf(DateTimeImmutable::class, $result->value());
        $this->assertEquals($value, (string)$result);
    }

    #[Test]
    public function create_a_task_deadline_with_empty_string_value()
    {
        // Arrange
        $value = '';

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(self::WRONG_VALUE_EXCEPTION_MESSAGE);

        // Act
        $result = new TaskDeadline($value, new DateTimeImmutable());
    }

    #[Test]
    public function create_a_task_deadline_without_hours()
    {
        // Arrange
        $value = now()->addMinute()->format('Y-m-d');

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(self::WRONG_VALUE_EXCEPTION_MESSAGE);

        // Act
        new TaskDeadline($value, new DateTimeImmutable());
    }

    #[Test]
    public function create_a_task_deadline_when_value_is_passed(): void
    {
        // Arrange
        $value = now()->addMinutes(-1)->format('Y-m-d H:i:s');

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(self::DEADLINE_MUST_BE_FUTURE_MESSAGE);

        // Act
        new TaskDeadline($value, new DateTimeImmutable());
    }
}
