<?php

namespace App\Entities;

use App\ValueObjects\Subtask\{SubtaskDeadline, SubtaskDescription, SubtaskTitle};
use App\ValueObjects\Task\{TaskDeadline, TaskDescription, TaskPriority, TaskStatus, TaskTitle};
use InvalidArgumentException;

final class Task
{
    private int $id;
    private int $boardId;
    private TaskTitle $title;
    private TaskStatus $status;
    private TaskPriority $priority;
    private ?TaskDeadline $deadline;
    private ?TaskDescription $description;

    public function __construct(
        int              $boardId,
        TaskTitle        $title,
        ?TaskDescription $description = null,
        ?TaskDeadline    $deadline = null,
    )
    {
        $this->boardId = $boardId;
        $this->title = $title;
        $this->status = TaskStatus::NOT_STARTED;
        $this->priority = TaskPriority::MEDIUM;
        $this->description = $description;
        $this->setDeadline($deadline);
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function start(): void
    {
        if($this->status !== TaskStatus::NOT_STARTED){
            throw new InvalidArgumentException('The task must not have started.');
        }
        $this->status = TaskStatus::IN_PROGRESS;
    }

    public function completed(): void
    {
        if($this->status !== TaskStatus::IN_PROGRESS){
            throw new InvalidArgumentException('The task must not have completed.');
        }
        $this->status = TaskStatus::COMPLETED;
    }

    public function reopen(): void
    {
        if($this->status !== TaskStatus::COMPLETED){
            throw new InvalidArgumentException('The task cannot reopened.');
        }
        $this->status = TaskStatus::NOT_STARTED;
    }

    public function setPriority(TaskPriority $priority): void
    {
        if($this->status === TaskStatus::COMPLETED){
            throw new InvalidArgumentException('The task cannot change the priority.');
        }
        $this->priority = $priority;
    }

    public function setDeadline(?TaskDeadline $deadline): void
    {
        if($this->status === TaskStatus::COMPLETED){
            throw new InvalidArgumentException('The task cannot change the deadline.');
        }
        if ($deadline && !$deadline->isFuture()) {
            throw new InvalidArgumentException('The deadline field must be a valid date');
        }
        $this->deadline = $deadline;
    }


    public function getId(): int { return $this->id; }
    public function getBoardId(): int { return $this->boardId; }
    public function getTitle(): TaskTitle { return $this->title; }
    public function getDescription(): ?TaskDescription { return $this->description; }
    public function getStatus(): TaskStatus { return $this->status; }
    public function getPriority(): TaskPriority { return $this->priority; }
    public function getDeadline(): ?TaskDeadline { return $this->deadline; }

    public function addSubtask(
        SubtaskTitle $title,
        bool $isCompletedTask,
        ?SubtaskDescription $description,
        ?SubtaskDeadline $deadline
    ): Subtask
    {
        return new Subtask(
            taskId: $this->id,
            title: $title,
            isCompletedTask: $isCompletedTask,
            description: $description,
            deadline: $deadline,
        );
    }
}
