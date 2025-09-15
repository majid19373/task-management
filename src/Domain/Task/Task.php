<?php

namespace Src\Domain\Task;

use Doctrine\ORM\Mapping\{Column, Embedded, Entity, GeneratedValue, Id, OneToMany, Table};
use Doctrine\Common\Collections\ArrayCollection;
use Src\Domain\Subtask\Subtask;
use Src\Domain\Subtask\SubtaskStatus;
use Src\Domain\Subtask\SubtaskTitle;
use Src\Domain\Subtask\{SubtaskDescription};
use DomainException;
use Doctrine\Common\Collections\Collection;

#[Entity, Table(name: "tasks")]
final class Task
{
    #[Id, Column(type: "string")]
    protected string $id;

    #[Column(name: "board_id", type: "string")]
    protected string $boardId;

    #[Column(name: 'title', type: 'task_title'), Embedded(class: TaskTitle::class, columnPrefix: false)]
    protected TaskTitle $title;

    #[Column(name: "status", enumType: TaskStatus::class)]
    protected TaskStatus $status;

    #[Column(name: "priority", enumType: TaskPriority::class)]
    protected TaskPriority $priority;

    #[Column(name: 'deadline', type: "task_deadline", nullable: true), Embedded(class: TaskDeadline::class, columnPrefix: false)]
    protected ?TaskDeadline $deadline;

    #[Column(name: 'description', type: "task_description", nullable: true), Embedded(class: TaskDescription::class, columnPrefix: false)]
    protected ?TaskDescription $description;

    #[OneToMany(targetEntity: Subtask::class, mappedBy: "task", cascade: ['persist', 'remove'], fetch: "LAZY", orphanRemoval: true)]
    protected Collection $subtasks;

    public function __construct(
        string           $id,
        string           $boardId,
        TaskTitle        $title,
        ?TaskDescription $description = null,
        ?TaskDeadline    $deadline = null,
    )
    {
        $this->id = $id;
        $this->boardId = $boardId;
        $this->title = $title;
        $this->status = TaskStatus::NOT_STARTED;
        $this->priority = TaskPriority::MEDIUM;
        $this->description = $description;
        $this->deadline = $deadline;
        $this->subtasks = new ArrayCollection();
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

    public function changeDeadline(TaskDeadline $deadline): void
    {
        if($this->status === TaskStatus::COMPLETED){
            throw new DomainException('The task cannot change the deadline.');
        }
        $this->deadline = $deadline;
    }


    public function getId(): string { return $this->id; }
    public function getBoardId(): string { return $this->boardId; }
    public function getTitle(): TaskTitle { return $this->title; }
    public function getDescription(): ?TaskDescription { return $this->description; }
    public function getStatus(): TaskStatus { return $this->status; }
    public function getPriority(): TaskPriority { return $this->priority; }
    public function getDeadline(): ?TaskDeadline { return $this->deadline; }
    public function getSubtasks(): Collection
    {
        return $this->subtasks;
    }

    public function addSubtask(
        string $subtaskId,
        SubtaskTitle $title,
        ?SubtaskDescription $description,
    ): void
    {
        if($this->status === TaskStatus::COMPLETED){
            throw new DomainException("Can not add a subtask to a completed task.");
        }
        $this->subtasks[] = new Subtask(
            id: $subtaskId,
            task: $this,
            title: $title,
            description: $description,
        );
    }

    public function getSubtask(string $subtaskId): Subtask
    {
        return array_find($this->subtasks->toArray(), fn($subtask) => $subtask->getId() === $subtaskId);

    }

    public function startSubtask(string $subtaskId): void
    {
        if($this->status === TaskStatus::COMPLETED){
            throw new DomainException('The subtask does not start if the task was completed.');
        }
        if($this->status !== TaskStatus::IN_PROGRESS){
            throw new DomainException('The subtask must not have started.');
        }

        $subtask = $this->getSubtask($subtaskId);
        $subtask->start();

        if($this->status === TaskStatus::NOT_STARTED){
            $this->status = TaskStatus::COMPLETED;
        }
    }

    public function completeSubtask(string $subtaskId): void
    {
        $subtask = $this->getSubtask($subtaskId);
        $subtask->complete();

        if(!collect($this->subtasks)->contains('status', '!=', SubtaskStatus::COMPLETED)){
            $this->status = TaskStatus::COMPLETED;
        }
    }

    public function reopenSubtask(string $subtaskId): void
    {
        $subtask = $this->getSubtask($subtaskId);
        $subtask->reopen();

        if($this->status === TaskStatus::COMPLETED){
            $this->status = TaskStatus::IN_PROGRESS;
        }
    }

    public function removeSubtask(string $subtaskId): void
    {
        $subtask = $this->getSubtask($subtaskId);
        $this->subtasks->removeElement($subtask);
        $subtask->remove();

        if(!count($this->subtasks)){
            $this->status = TaskStatus::NOT_STARTED;
        }
    }
}
