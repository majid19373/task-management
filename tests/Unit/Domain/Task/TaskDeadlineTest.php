<?php

namespace Tests\Unit\Domain\Task;

use DomainException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Task\TaskDeadline;
use DateTimeImmutable;

final class TaskDeadlineTest extends TestCase
{
    public static function provideInvalidDate(): array
    {
        return [
            'EMPTY' => [''],
            'WITHOUT_HOUR' => [now()->addMinute()->format('Y-m-d')],
        ];
    }

    #[Test]
    public function create_a_task_deadline()
    {
        // Arrange
        $value = now()->addMinute()->format('Y-m-d H:i:s');

        // Act
        $result = new TaskDeadline($value);

        // Assert
        $this->assertInstanceOf(DateTimeImmutable::class, $result->value());
        $this->assertEquals($value, (string)$result);
        $this->assertTrue($result->isFuture(new DateTimeImmutable()));
    }

    #[Test]
    #[DataProvider('provideInvalidDate')]
    public function create_a_task_deadline_when_date_is_invalid()
    {
        // Arrange
        $value = '';

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('The deadline field must be a valid date.');

        // Act
        new TaskDeadline($value);
    }

    #[Test]
    public function create_a_task_deadline_when_value_is_passed(): void
    {
        // Arrange
        $value = now()->addMinutes(-1)->format('Y-m-d H:i:s');
        $sut = new TaskDeadline($value);

        // Act
        $sut->isFuture(new DateTimeImmutable());

        // Assert
        $this->assertFalse($sut->isFuture(new DateTimeImmutable()));
    }
}
