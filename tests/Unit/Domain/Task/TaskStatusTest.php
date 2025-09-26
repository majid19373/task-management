<?php

namespace Tests\Unit\Domain\Task;

use DomainException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Task\TaskStatus;

final class TaskStatusTest extends TestCase
{
    private const string EXCEPTION_MESSAGE = 'Status task is not valid.';

    #[Test]
    public function validate_task_status()
    {
        // Arrange
        $value = TaskStatus::NOT_STARTED->value;

        // Act
        TaskStatus::validate($value);

        // Assert
        $this->assertTrue(true);
    }

    #[Test]
    public function validate_task_status_with_wrong_value()
    {
        // Arrange
        $value = 'not_valid';

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(self::EXCEPTION_MESSAGE);

        // Act
        TaskStatus::validate($value);
    }
}
