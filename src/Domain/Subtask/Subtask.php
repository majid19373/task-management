<?php

namespace Src\Domain\Subtask;

use Src\Domain\Task\Task;
use Doctrine\ORM\Mapping\{Column, Embedded, Entity, GeneratedValue, Id, JoinColumn, ManyToOne, Table};
use DomainException;

#[Entity, Table(name: "subtasks")]
final class Subtask
{
    #[Id, Column(type: "integer"), GeneratedValue()]
    protected int $id;

    #[JoinColumn(name: 'task_id', nullable: false), ManyToOne(targetEntity: Task::class, inversedBy: 'subtasks')]
    protected Task $task;

    #[Column(name: 'title', type: 'subtask_title'), Embedded(class: SubtaskTitle::class, columnPrefix: false)]
    protected SubtaskTitle $title;

    #[Column(name: "status", enumType: SubtaskStatus::class)]
    protected SubtaskStatus $status;

    #[Column(name: 'description', type: "subtask_description", nullable: true), Embedded(class: SubtaskDescription::class, columnPrefix: false)]
    protected ?SubtaskDescription $description;

    public function __construct(
        int                 $id,
        Task                $task,
        SubtaskTitle        $title,
        ?SubtaskDescription $description = null,
    )
    {
        $this->id = $id;
        $this->task = $task;
        $this->title = $title;
        $this->status = SubtaskStatus::NOT_STARTED;
        $this->description = $description;
    }

    public function start(): void
    {
        if($this->status !== SubtaskStatus::NOT_STARTED){
            throw new DomainException('The subtask must not have started.');
        }
        $this->status = SubtaskStatus::IN_PROGRESS;
    }

    public function complete(): void
    {
        if($this->status !== SubtaskStatus::IN_PROGRESS){
            throw new DomainException('The subtask must not have completed.');
        }
        $this->status = SubtaskStatus::COMPLETED;
    }

    public function reopen(): void
    {
        if($this->status !== SubtaskStatus::COMPLETED){
            throw new DomainException('The subtask cannot reopened.');
        }
        $this->status = SubtaskStatus::NOT_STARTED;
    }

    public function getId(): int { return $this->id; }
    public function getTitle(): SubtaskTitle { return $this->title; }
    public function getDescription(): ?SubtaskDescription { return $this->description; }
    public function getStatus(): SubtaskStatus { return $this->status; }
}
