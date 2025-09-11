<?php

namespace Src\Infrastructure\Laravel\Providers;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Exception\TypesException;
use Doctrine\DBAL\Types\Type;
use Src\Infrastructure\Persistence\Doctrine\Types\Board\{BoardDescriptionType, BoardNameType};
use Illuminate\Support\ServiceProvider;
use Src\Infrastructure\Persistence\Doctrine\Types\Subtask\{SubtaskDescriptionType, SubtaskTitleType};
use Src\Infrastructure\Persistence\Doctrine\Types\Task\{TaskDeadlineType, TaskDescriptionType, TaskTitleType};

class DoctrineProvider extends ServiceProvider
{
    /**
     * @throws Exception
     * @throws TypesException
     */
    public function register(): void
    {
        $this->BoardTypes();
        $this->TaskTypes();
        $this->SubtaskTypes();
    }

    public function boot(): void
    {
        //
    }

    /**
     * @throws Exception
     * @throws TypesException
     */
    public function BoardTypes(): void
    {
        if (!Type::hasType(BoardNameType::NAME)) {
            Type::addType(BoardNameType::NAME, BoardNameType::class);
        }

        if (!Type::hasType(BoardDescriptionType::NAME)) {
            Type::addType(BoardDescriptionType::NAME, BoardDescriptionType::class);
        }
    }

    /**
     * @throws Exception
     * @throws TypesException
     */
    public function TaskTypes(): void
    {
        if (!Type::hasType(TaskTitleType::NAME)) {
            Type::addType(TaskTitleType::NAME, TaskTitleType::class);
        }

        if (!Type::hasType(TaskDescriptionType::NAME)) {
            Type::addType(TaskDescriptionType::NAME, TaskDescriptionType::class);
        }

        if (!Type::hasType(TaskDeadlineType::NAME)) {
            Type::addType(TaskDeadlineType::NAME, TaskDeadlineType::class);
        }
    }

    /**
     * @throws Exception
     * @throws TypesException
     */
    public function SubtaskTypes(): void
    {
        if (!Type::hasType(SubtaskTitleType::NAME)) {
            Type::addType(SubtaskTitleType::NAME, SubtaskTitleType::class);
        }

        if (!Type::hasType(SubtaskDescriptionType::NAME)) {
            Type::addType(SubtaskDescriptionType::NAME, SubtaskDescriptionType::class);
        }
    }
}
