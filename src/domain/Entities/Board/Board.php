<?php

namespace Src\domain\Entities\Board;


use Doctrine\ORM\Mapping\{Column, Embedded, Entity, GeneratedValue, Id, Table, UniqueConstraint};
use Src\domain\Entities\Task\Task;
use Src\domain\Entities\Task\ValueObjects\{TaskDeadline, TaskDescription, TaskTitle};
use DomainException;

#[Entity, Table(name: "boards"), UniqueConstraint(name: 'user_board_unique', columns: ['user_id', 'name'])]
final class Board
{
    #[Id, Column(type: "integer"), GeneratedValue()]
    protected int $id;

    #[Column(type: "integer")]
    protected int $userId;

    #[Column(name: "name", type: "string", length: 50), Embedded(class: ValueObjects\BoardName::class, columnPrefix: false)]
    protected ValueObjects\BoardName $name;

    #[Column(name: "description", type: "string", length: 200, nullable: true), Embedded(class: TaskDescription::class, columnPrefix: false)]
    protected ?ValueObjects\BoardDescription $description;

    /**
     * @throws DomainException
     */
    public function __construct(
        bool                           $existsByUserIdAndName,
        ValueObjects\BoardName         $name,
        int                            $userId,
        ?ValueObjects\BoardDescription $description = null
    )
    {
        if($existsByUserIdAndName){
            throw new DomainException('Board name already exists for this user.');
        }
        $this->name = $name;
        $this->userId = $userId;
        $this->description = $description;
    }

    public function getId(): int { return $this->id; }
    public function getName(): ValueObjects\BoardName { return $this->name; }
    public function getUserId(): int { return $this->userId; }
    public function getDescription(): ?ValueObjects\BoardDescription { return $this->description; }

    public function addTask(TaskTitle $title, ?TaskDescription $description, ?TaskDeadline $deadline): Task
    {
        return new Task(
            board: $this,
            title: $title,
            description: $description,
            deadline: $deadline,
        );
    }
}
