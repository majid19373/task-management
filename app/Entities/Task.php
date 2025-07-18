<?php

namespace App\Entities;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use Carbon\Carbon;
use DateTimeInterface;
use InvalidArgumentException;

final class Task
{
    private int $id;
    private int $boardId;
    private string $title;
    private string $status;
    private string $priority;
    private ?Carbon $deadline;
    private ?string $description;

    public function __construct(
        int $id, string $title, int $boardId, ?string $description = null, ?string $status = TaskStatusEnum::NOT_STARTED->value,
        ?string $priority = TaskPriorityEnum::MEDIUM->value, ?Carbon $deadline = null
    )
    {
        $this->id = $id;
        $this->setTitle($title);
        $this->boardId = $boardId;
        $this->setDescription($description);
        $this->setStatus($status);
        $this->setPriority($priority);
        $this->setDeadline($deadline);
    }
    public function update(string $title, ?string $description = null): void
    {
        $this->setTitle($title);
        $this->description = $description;
    }

    public function setTitle(string $title): void
    {
        $length = strlen($title);
        if ($length < 5 || $length > 100) {
            throw new InvalidArgumentException("Board title must be between 5 and 100 characters.");
        }

        $this->title = $title;
    }

    public function setDescription(?string $description): void
    {
        if ($description && strlen($description) > 500) {
            throw new InvalidArgumentException("Board description must be less than 200 characters.");
        }

        $this->description = $description;
    }

    public function setStatus(string $status): void
    {
        if (!in_array($status, TaskStatusEnum::toArray())) {
            throw new InvalidArgumentException("Staus is not a valid Task status.");
        }

        $this->status = $status;
    }

    public function setPriority(string $priority): void
    {
        if (!in_array($priority, TaskPriorityEnum::toArray())) {
            throw new InvalidArgumentException("Priority is not a valid Task priority.");
        }

        $this->priority = $priority;
    }

    public function setDeadline(?Carbon $deadline): void
    {
        if ($deadline && !$deadline instanceof DateTimeInterface) {
            throw new InvalidArgumentException("Deadline must be a valid date.");
        }

        $this->deadline = $deadline;

    }


    public function getId(): int { return $this->id; }
    public function getBoardId(): int { return $this->boardId; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): ?string { return $this->description; }
    public function getStatus(): ?string { return $this->status; }
    public function getPriority(): ?string { return $this->priority; }
    public function getDeadline(): ?string { return $this->deadline; }
}
