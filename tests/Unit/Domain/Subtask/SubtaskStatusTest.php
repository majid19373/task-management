<?php

namespace Tests\Unit\Domain\Subtask;

use DomainException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Subtask\SubtaskStatus;

final class SubtaskStatusTest extends TestCase
{
    private const string EXCEPTION_MESSAGE = 'Subtask status is not valid.';

    #[Test]
    public function validate_subtask_status()
    {
        // Arrange
        $value = SubtaskStatus::NOT_STARTED->value;

        // Act
        SubtaskStatus::validate($value);

        // Assert
        $this->assertTrue(true);
    }

    #[Test]
    public function validate_subtask_status_with_wrong_value()
    {
        // Arrange
        $value = 'not_valid';

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(self::EXCEPTION_MESSAGE);

        // Act
        SubtaskStatus::validate($value);
    }
}
