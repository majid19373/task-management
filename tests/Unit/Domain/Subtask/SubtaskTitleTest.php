<?php

namespace Tests\Unit\Domain\Subtask;

use DomainException;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Src\Domain\Subtask\SubtaskTitle;

final class SubtaskTitleTest extends TestCase
{
    public static function provideValidTitleLengths(): array
    {
        return [
            'MAX' => [100],
            'MIN' => [5],
        ];
    }
    public static function provideInvalidTitleLengths(): array
    {
        return [
            'TOO_SHORT' => [0],
            'TOO_LONG' => [101],
        ];
    }

    #[Test]
    #[DataProvider('provideValidTitleLengths')]
    public function create_a_subtask_name(int $length)
    {
        // Arrange
        $value = Str::random($length);

        // Act
        $result = new SubtaskTitle($value);

        // Assert
        $this->assertEquals($value, $result->value());
        $this->assertEquals($value, (string)$result);
    }

    #[Test]
    #[DataProvider('provideInvalidTitleLengths')]
    public function creating_a_subtask_name_when_value_length_be_wrong(int $length): void
    {
        // Arrange
        $value = Str::random($length);

        // Expect
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Subtask title must be between 5 and 100 characters.');

        // Act
        new SubtaskTitle($value);
    }
}
