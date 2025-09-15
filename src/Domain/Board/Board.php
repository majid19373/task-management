<?php

namespace Src\Domain\Board;


use Doctrine\ORM\Mapping\{Column, Embedded, Entity, Id, Table, UniqueConstraint};
use Src\Domain\Task\Task;
use Src\Domain\Task\TaskDeadline;
use Src\Domain\Task\TaskTitle;
use Src\Domain\Task\{TaskDescription};
use DomainException;

#[Entity, Table(name: "boards"), UniqueConstraint(name: 'user_board_unique', columns: ['user_id', 'name'])]
final class Board
{
    #[Id, Column(type: "string")]
    protected string $id;

    #[Column(type: "integer")]
    protected int $userId;

    #[Column(name: "name", type: "board_name"), Embedded(class: BoardName::class, columnPrefix: false)]
    protected BoardName $name;

    #[Column(name: 'description', type: "board_description", nullable: true), Embedded(class: BoardDescription::class, columnPrefix: false)]
    protected ?BoardDescription $description;

    /**
     * @throws DomainException
     */
    public function __construct(
        string            $id,
        bool              $existsByUserIdAndName,
        BoardName         $name,
        int               $userId,
        ?BoardDescription $description = null
    )
    {
        if($existsByUserIdAndName){
            throw new DomainException('Board name already exists for this user.');
        }
        $this->id = $id;
        $this->name = $name;
        $this->userId = $userId;
        $this->description = $description;
    }

    public function getId(): string { return $this->id; }
    public function getName(): BoardName { return $this->name; }
    public function getDescription(): ?BoardDescription { return $this->description; }

    public function addTask(string $taskId, TaskTitle $title, ?TaskDescription $description, ?TaskDeadline $deadline): Task
    {
        return new Task(
            id: $taskId,
            boardId: $this->id,
            title: $title,
            description: $description,
            deadline: $deadline,
        );
    }
}
