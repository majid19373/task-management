<?php

namespace App\Entities;

use App\ValueObjects\Subtask\SubtaskDeadline;
use App\ValueObjects\Subtask\SubtaskDescription;
use App\ValueObjects\Subtask\SubtaskPriority;
use App\ValueObjects\Subtask\SubtaskStatus;
use App\ValueObjects\Subtask\SubtaskTitle;
use App\ValueObjects\Task\TaskStatus;
use InvalidArgumentException;

final class Subtask
{
    private int $id;
    private int $taskId;
    private SubtaskTitle $title;
    private SubtaskStatus $status;
    private SubtaskPriority $priority;
    private ?SubtaskDeadline $deadline;
    private ?SubtaskDescription $description;

    public function __construct(
        int                 $taskId,
        SubtaskTitle        $title,
        bool                $isCompletedTask,
        ?SubtaskDescription $description = null,
        ?SubtaskDeadline    $deadline = null,
    )
    {
        if($isCompletedTask){
            throw new InvalidArgumentException("Can not add a subtask to a completed task.");
        }
        $this->taskId = $taskId;
        $this->title = $title;
        $this->status = SubtaskStatus::NOT_STARTED;
        $this->priority = SubtaskPriority::MEDIUM;
        $this->description = $description;
        $this->setDeadline($deadline);
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setDeadline(?SubtaskDeadline $deadline): void
    {
        if($this->status === SubtaskStatus::COMPLETED){
            throw new InvalidArgumentException('The subtask cannot change the deadline.');
        }
        if ($deadline && !$deadline->isFuture()) {
            throw new InvalidArgumentException('The deadline field must be a valid date');
        }
        $this->deadline = $deadline;
    }



    public function start(TaskStatus $taskStatus): void
    {
        if($taskStatus === TaskStatus::COMPLETED){
            throw new InvalidArgumentException('The subtask does not start if the task was completed.');
        }
        if($taskStatus !== TaskStatus::IN_PROGRESS && $this->status !== SubtaskStatus::NOT_STARTED){
            throw new InvalidArgumentException('The subtask must not have started.');
        }
        $this->status = SubtaskStatus::IN_PROGRESS;
    }

    public function completed(): void
    {
        if($this->status !== SubtaskStatus::IN_PROGRESS){
            throw new InvalidArgumentException('The task must not have completed.');
        }
        $this->status = SubtaskStatus::COMPLETED;
    }

    public function getId(): int { return $this->id; }
    public function getTaskId(): ?int { return $this->taskId; }
    public function getTitle(): SubtaskTitle { return $this->title; }
    public function getDescription(): ?SubtaskDescription { return $this->description; }
    public function getStatus(): SubtaskStatus { return $this->status; }
    public function getPriority(): SubtaskPriority { return $this->priority; }
    public function getDeadline(): ?SubtaskDeadline { return $this->deadline; }
}
