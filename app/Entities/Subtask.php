<?php

namespace App\Entities;

use App\ValueObjects\Subtask\SubtaskDescription;
use App\ValueObjects\Subtask\SubtaskStatus;
use App\ValueObjects\Subtask\SubtaskTitle;
use App\ValueObjects\Task\TaskStatus;
use DomainException;

final class Subtask
{
    private int $id;
    private int $taskId;
    private SubtaskTitle $title;
    private SubtaskStatus $status;
    private ?SubtaskDescription $description;

    private function __construct(
        int                 $taskId,
        SubtaskTitle        $title,
        SubtaskStatus        $status,
        ?SubtaskDescription $description = null,
    )
    {
        $this->taskId = $taskId;
        $this->title = $title;
        $this->status = $status;
        $this->description = $description;
    }

    public static function createNew(
        int                 $taskId,
        SubtaskTitle        $title,
        bool                $isCompletedTask,
        ?SubtaskDescription $description = null,
    ): Subtask
    {
        if($isCompletedTask){
            throw new DomainException("Can not add a subtask to a completed task.");
        }
        return new self(
            $taskId,
            $title,
            SubtaskStatus::NOT_STARTED,
            $description,
        );
    }

    public static function reconstitute(
        int                 $id,
        int                 $taskId,
        SubtaskTitle        $title,
        SubtaskStatus       $status,
        ?SubtaskDescription $description = null,
    ): Subtask
    {
        $task = new self(
            $taskId,
            $title,
            $status,
            $description,
        );
        $task->setId($id);
        return $task;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function start(TaskStatus $taskStatus): void
    {
        if($taskStatus === TaskStatus::COMPLETED){
            throw new DomainException('The subtask does not start if the task was completed.');
        }
        if($taskStatus !== TaskStatus::IN_PROGRESS && $this->status !== SubtaskStatus::NOT_STARTED){
            throw new DomainException('The subtask must not have started.');
        }
        $this->status = SubtaskStatus::IN_PROGRESS;
    }

    public function completed(): void
    {
        if($this->status !== SubtaskStatus::IN_PROGRESS){
            throw new DomainException('The task must not have completed.');
        }
        $this->status = SubtaskStatus::COMPLETED;
    }

    public function getId(): int { return $this->id; }
    public function getTaskId(): ?int { return $this->taskId; }
    public function getTitle(): SubtaskTitle { return $this->title; }
    public function getDescription(): ?SubtaskDescription { return $this->description; }
    public function getStatus(): SubtaskStatus { return $this->status; }
}
