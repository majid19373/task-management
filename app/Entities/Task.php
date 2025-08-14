<?php

namespace App\Entities;

use App\ValueObjects\Subtask\{SubtaskDeadline, SubtaskDescription, SubtaskTitle};
use App\ValueObjects\Task\{TaskDeadline, TaskDescription, TaskPriority, TaskStatus, TaskTitle};
use DomainException;

final class Task
{
    private int $id;
    private int $boardId;
    private TaskTitle $title;
    private TaskStatus $status;
    private TaskPriority $priority;
    private ?TaskDeadline $deadline;
    private ?TaskDescription $description;

    private function __construct(
        int              $boardId,
        TaskTitle        $title,
        TaskStatus       $status,
        TaskPriority     $priority,
        ?TaskDescription $description = null,
        ?TaskDeadline    $deadline = null,
    )
    {
        $this->boardId = $boardId;
        $this->title = $title;
        $this->status = $status;
        $this->priority = $priority;
        $this->description = $description;
        $this->deadline = $deadline;
    }

    public static function createNew(
        int              $boardId,
        TaskTitle        $title,
        ?TaskDescription $description = null,
        ?TaskDeadline    $deadline = null,
    ): Task
    {
        return new self(
            $boardId,
            $title,
            TaskStatus::NOT_STARTED,
            TaskPriority::MEDIUM,
            $description,
            $deadline
        );
    }

    public static function reconstitute(
        int              $id,
        int              $boardId,
        TaskTitle        $title,
        TaskStatus       $status,
        TaskPriority     $priority,
        ?TaskDescription $description = null,
        ?TaskDeadline    $deadline = null,
    ): Task
    {
        $task = new self(
            $boardId,
            $title,
            $status,
            $priority,
            $description,
            $deadline
        );
        $task->setId($id);
        return $task;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function start(): void
    {
        if($this->status !== TaskStatus::NOT_STARTED){
            throw new DomainException('The task must not have started.');
        }
        $this->status = TaskStatus::IN_PROGRESS;
    }

    public function complete(): void
    {
        if($this->status !== TaskStatus::IN_PROGRESS){
            throw new DomainException('The task must not have completed.');
        }
        $this->status = TaskStatus::COMPLETED;
    }

    public function reopen(): void
    {
        if($this->status !== TaskStatus::COMPLETED){
            throw new DomainException('The task cannot reopened.');
        }
        $this->status = TaskStatus::NOT_STARTED;
    }

    public function prioritize(TaskPriority $priority): void
    {
        if($this->status === TaskStatus::COMPLETED){
            throw new DomainException('The task cannot change the priority.');
        }
        $this->priority = $priority;
    }

    public function setDeadline(?TaskDeadline $deadline): void
    {
        if($this->status === TaskStatus::COMPLETED){
            throw new DomainException('The task cannot change the deadline.');
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
