<?php

namespace App\Entities;


use App\ValueObjects\Task\{TaskDeadline, TaskDescription, TaskTitle};
use App\ValueObjects\Board\{BoardName, BoardDescription};
use DomainException;

final class Board
{
    private int $id;
    private int $userId;
    private BoardName $name;
    private ?BoardDescription $description;

    /**
     * @throws DomainException
     */
    private function __construct(
        BoardName $name,
        int $userId,
        ?BoardDescription $description = null
    )
    {
        $this->name = $name;
        $this->userId = $userId;
        $this->description = $description;
    }

    /**
     * @throws DomainException
     */
    public static function createNew(
        bool $existsByUserIdAndName,
        BoardName $name,
        int $userId,
        ?BoardDescription $description = null
    ): Board
    {
        if($existsByUserIdAndName){
            throw new DomainException('Board name already exists for this user.');
        }
        return new self($name, $userId, $description);
    }

    public static function reconstitute(
        int $id,
        BoardName $name,
        int $userId,
        ?BoardDescription $description = null
    ): Board
    {
        $board = new self($name, $userId, $description);
        $board->setId($id);
        return $board;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int { return $this->id; }
    public function getName(): BoardName { return $this->name; }
    public function getUserId(): int { return $this->userId; }
    public function getDescription(): ?BoardDescription { return $this->description; }

    public function addTask(TaskTitle $title, ?TaskDescription $description, ?TaskDeadline $deadline): Task
    {
        return new Task(
            boardId: $this->id,
            title: $title,
            description: $description,
            deadline: $deadline,
        );
    }
}
