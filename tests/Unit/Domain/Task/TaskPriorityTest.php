<?php

namespace Tests\Unit\Domain\Task;

use DomainException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Task\TaskPriority;

final class TaskPriorityTest extends TestCase
{
    private const string EXCEPTION_MESSAGE = 'Priority task is not valid.';

    #[Test]
    public function validate_task_priority()
    {
        // Arrange
        $value = TaskPriority::LOW->value;

        // Act
        TaskPriority::validate($value);

        // Assert
        $this->assertTrue(true);
    }

    #[Test]
    public function validate_task_priority_with_wrong_value()
    {
        // Arrange
        $value = 'not_valid';

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(self::EXCEPTION_MESSAGE);

        // Act
        TaskPriority::validate($value);
    }
}
