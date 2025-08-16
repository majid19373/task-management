<?php

namespace App\Entities;

use App\ValueObjects\Task\{TaskDeadline, TaskDescription, TaskPriority, TaskStatus, TaskTitle};
use App\ValueObjects\Subtask\{SubtaskDescription, SubtaskStatus, SubtaskTitle};
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

    /** @var Array<Subtask> */
    private array $subtasks;

    private function __construct(
        int              $boardId,
        TaskTitle        $title,
        TaskStatus       $status,
        TaskPriority     $priority,
        ?TaskDescription $description = null,
        ?TaskDeadline    $deadline = null,
        ?array           $subtasks = []
    )
    {
        $this->boardId = $boardId;
        $this->title = $title;
        $this->status = $status;
        $this->priority = $priority;
        $this->description = $description;
        $this->deadline = $deadline;
        $this->subtasks = $subtasks;
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
        ?array           $subtasks = [],
    ): Task
    {
        $task = new self(
            $boardId,
            $title,
            $status,
            $priority,
            $description,
            $deadline,
            $subtasks
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
        if(collect($this->subtasks)->contains('status', '!=', SubtaskStatus::COMPLETED)){
            throw new DomainException('The task must not complete.');
        }
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
    ): Subtask
    {
        return Subtask::createNew(
            taskId: $this->id,
            title: $title,
            isCompletedTask: $isCompletedTask,
            description: $description,
        );
    }

    public function getSubtask(int $subtaskId): Subtask
    {
        return collect($this->subtasks)->firstWhere('id', $subtaskId);
    }

    public function startSubtask(int $subtaskId): void
    {
        $subtask = $this->getSubtask($subtaskId);
        $subtask->start($this->getStatus());

        if($this->status === TaskStatus::NOT_STARTED){
            $this->status = TaskStatus::COMPLETED;
        }
    }

    public function completeSubtask(int $subtaskId): void
    {
        $subtask = $this->getSubtask($subtaskId);
        $subtask->complete();

        if(!collect($this->subtasks)->contains('status', '!=', SubtaskStatus::COMPLETED)){
            $this->status = TaskStatus::COMPLETED;
        }
    }

    public function reopenSubtask(int $subtaskId): void
    {
        $subtask = $this->getSubtask($subtaskId);
        $subtask->reopen();

        if($this->status === TaskStatus::COMPLETED){
            $this->status = TaskStatus::IN_PROGRESS;
        }
    }
}
