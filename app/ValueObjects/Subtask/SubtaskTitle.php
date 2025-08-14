<?php

namespace App\ValueObjects\Subtask;

use DomainException;

final class SubtaskTitle
{
    private string $title;

    private function __construct(string $title)
    {
        $this->title = $title;
    }

    public static function createNew(string $title): SubtaskTitle
    {
        $length = strlen($title);
        if ($length < 5 || $length > 100) {
            throw new DomainException("Subtask title must be between 5 and 100 characters.");
        }
        return new self($title);
    }

    public static function reconstitute(string $title): SubtaskTitle
    {
        return new self($title);
    }

    public function value(): string
    {
        return $this->title;
    }
}
