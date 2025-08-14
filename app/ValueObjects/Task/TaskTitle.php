<?php

namespace App\ValueObjects\Task;

use DomainException;

final class TaskTitle
{
    private string $title;

    private function __construct(string $title)
    {
        $this->title = $title;
    }


    public static function createNew(string $title): TaskTitle
    {
        $length = strlen($title);
        if ($length < 5 || $length > 100) {
            throw new DomainException("Task title must be between 5 and 100 characters.");
        }
        return new self($title);
    }

    public static function reconstitute(string $title): TaskTitle
    {
        return new self($title);
    }

    public function value(): string
    {
        return $this->title;
    }
}
