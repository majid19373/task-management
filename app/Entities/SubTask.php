<?php

namespace App\Entities;

use App\ValueObjects\Task\TaskDeadline;
use App\ValueObjects\Task\TaskDescription;
use App\ValueObjects\Task\TaskPriority;
use App\ValueObjects\Task\TaskStatus;
use App\ValueObjects\Task\TaskTitle;

final class SubTask
{
    private int $id;
    private ?int $taskId;
    private int $boardId;
    private TaskTitle $title;
    private TaskStatus $status;
    private TaskPriority $priority;
    private ?TaskDeadline $deadline;
    private ?TaskDescription $description;

    public function __construct(
        int $boardId,
        TaskTitle $title,
        int $taskId,
        ?TaskDescription $description = null,
        ?TaskDeadline $deadline = null,
    )
    {
        $this->boardId = $boardId;
        $this->title = $title;
        $this->taskId = $taskId;
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
    public function getTaskId(): ?int { return $this->taskId; }
    public function getBoardId(): int { return $this->boardId; }
    public function getTitle(): TaskTitle { return $this->title; }
    public function getDescription(): ?TaskDescription { return $this->description; }
    public function getStatus(): TaskStatus { return $this->status; }
    public function getPriority(): TaskPriority { return $this->priority; }
    public function getDeadline(): ?TaskDeadline { return $this->deadline; }
}
