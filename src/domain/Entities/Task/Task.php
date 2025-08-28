<?php

namespace Src\domain\Entities\Task;

use Doctrine\ORM\Mapping\{Column, Embedded, Entity, GeneratedValue, Id, JoinColumn, ManyToOne, OneToMany, Table};
use Doctrine\Common\Collections\ArrayCollection;
use Src\domain\Entities\Board\Board;
use Src\domain\Entities\Subtask\Subtask;
use Src\domain\Entities\Task\ValueObjects\{TaskDeadline, TaskDescription, TaskPriority, TaskStatus, TaskTitle};
use Src\domain\Entities\Subtask\ValueObjects\{SubtaskDescription, SubtaskStatus, SubtaskTitle};
use DomainException;
use Doctrine\Common\Collections\Collection;

#[Entity, Table(name: "tasks")]
final class Task
{
    #[Id, Column(type: "integer"), GeneratedValue()]
    protected int $id;

    #[JoinColumn(name: "board_id", referencedColumnName: "id", nullable: false, onDelete: 'CASCADE'), ManyToOne(targetEntity: Board::class, inversedBy: "task")]
    protected Board $board;

    #[Column(name: "title", type: "string", length: 100), Embedded(class: TaskTitle::class, columnPrefix: false)]
    protected TaskTitle $title;

    #[Column(name: "status", enumType: TaskStatus::class)]
    protected TaskStatus $status;

    #[Column(name: "priority", enumType: TaskPriority::class)]
    protected TaskPriority $priority;

    #[Column(name: "deadline", type: "string", nullable: true), Embedded(class: TaskDeadline::class, columnPrefix: false)]
    protected ?TaskDeadline $deadline;

    #[Column(name: "description", type: "string", length: 500, nullable: true), Embedded(class: TaskDescription::class, columnPrefix: false)]
    protected ?TaskDescription $description;

    #[OneToMany(targetEntity: Subtask::class, mappedBy: "task", cascade: ['persist', 'remove'], orphanRemoval: true)]
    protected Collection $subtasks;

    public function __construct(
        Board                         $board,
        TaskTitle        $title,
        ?TaskDescription $description = null,
        ?TaskDeadline    $deadline = null,
    )
    {
        $this->board = $board;
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


    public function getId(): int { return $this->id; }
    public function getBoard(): Board { return $this->board; }
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
        SubtaskTitle $title,
        ?SubtaskDescription $description,
    ): void
    {
        if($this->status === TaskStatus::COMPLETED){
            throw new DomainException("Can not add a subtask to a completed task.");
        }
        $this->subtasks[] = new Subtask(
            task: $this,
            title: $title,
            description: $description,
        );
    }

    public function getSubtask(int $subtaskId): Subtask
    {
        return collect($this->subtasks)->firstWhere('id', $subtaskId);
    }

    public function startSubtask(int $subtaskId): void
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
