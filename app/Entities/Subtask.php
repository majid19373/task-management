<?php

namespace App\Entities;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use App\ValueObjects\Task\TaskDeadline;
use App\ValueObjects\Task\TaskDescription;
use App\ValueObjects\Task\TaskPriority;
use App\ValueObjects\Task\TaskStatus;
use App\ValueObjects\Task\TaskTitle;

final class Subtask
{
    private int $id;
    private int $taskId;
    private TaskTitle $title;
    private TaskStatus $status;
    private TaskPriority $priority;
    private ?TaskDeadline $deadline;
    private ?TaskDescription $description;

    public function __construct(
        int $taskId,
        TaskTitle $title,
        ?TaskDescription $description = null,
        ?TaskDeadline $deadline = null,
    )
    {
        $this->taskId = $taskId;
        $this->title = $title;
        $this->description = $description;
        $this->deadline = $deadline;
        $this->status = new TaskStatus(TaskStatusEnum::NOT_STARTED->value);
        $this->priority = new TaskPriority(TaskPriorityEnum::MEDIUM->value);
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
    public function getTitle(): TaskTitle { return $this->title; }
    public function getDescription(): ?TaskDescription { return $this->description; }
    public function getStatus(): TaskStatus { return $this->status; }
    public function getPriority(): TaskPriority { return $this->priority; }
    public function getDeadline(): ?TaskDeadline { return $this->deadline; }
}
