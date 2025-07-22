<?php

namespace App\Entities;

use App\ValueObjects\Task\TaskDeadline;
use App\ValueObjects\Task\TaskDescription;
use App\ValueObjects\Task\TaskPriority;
use App\ValueObjects\Task\TaskStatus;
use App\ValueObjects\Task\TaskTitle;

final class Task
{
    private int $id;
    private ?int $parentId;
    private int $boardId;
    private TaskTitle $title;
    private TaskStatus $status;
    private TaskPriority $priority;
    private ?TaskDeadline $deadline;
    private ?TaskDescription $description;

    public function __construct(
        int $boardId,
        TaskTitle $title,
        ?int $parentId = null,
        ?TaskDescription $description = null,
        ?TaskDeadline $deadline = null,
    )
    {
        $this->boardId = $boardId;
        $this->title = $title;
        $this->parentId = $parentId;
        $this->description = $description;
        $this->deadline = $deadline;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setStatus(TaskStatus $status): void
    {
        $this->status = $status;
    }

    public function setPriority(TaskPriority $priority): void
    {
        $this->priority = $priority;
    }

    public function setDeadline(TaskDeadline $deadline): void
    {
        $this->deadline = $deadline;
    }


    public function getId(): int { return $this->id; }
    public function getParentId(): ?int { return $this->parentId; }
    public function getBoardId(): int { return $this->boardId; }
    public function getTitle(): string { return $this->title->getTitle(); }
    public function getDescription(): ?string { return $this->description->getDescription(); }
    public function getStatus(): string { return $this->status->getStatus(); }
    public function getPriority(): string { return $this->priority->getPriority(); }
    public function getDeadline(): ?string { return $this->deadline->getDeadline(); }
}
