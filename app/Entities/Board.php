<?php

namespace App\Entities;


use App\ValueObjects\Task\{TaskDeadline, TaskDescription, TaskTitle};
use App\ValueObjects\Board\{BoardName, BoardDescription};
use Exception;

final class Board
{
    private int $id;
    private int $userId;
    private BoardName $name;
    private ?BoardDescription $description;

    /**
     * @throws Exception
     */
    public function __construct(
        bool $existsByUserIdAndName,
        BoardName $name,
        int $userId,
        ?BoardDescription $description = null
    )
    {
        $this->setName($existsByUserIdAndName, $name);
        $this->userId = $userId;
        $this->description = $description;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @throws Exception
     */
    public function setName(bool $existsByUserIdAndName, BoardName $name): void
    {
        if($existsByUserIdAndName){
            throw new Exception('Board name already exists for this user.');
        }
        $this->name = $name;
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
