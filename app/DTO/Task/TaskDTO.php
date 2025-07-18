<?php

namespace App\DTO\Task;

use App\DTO\BaseDTO;
use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use Carbon\Carbon;

final class TaskDTO extends BaseDTO
{
    public ?Carbon $deadline = null;
    public function __construct(
        public ?int $id = null,
        public ?int $board_id = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $status = TaskStatusEnum::NOT_STARTED->value,
        public ?string $priority = TaskPriorityEnum::MEDIUM->value,
        ?string $deadline = null,
    )
    {
        $this->setDeadline($deadline);
    }

    public function setDeadline(?string $deadline): void
    {
        if($deadline){
            $this->deadline = new Carbon($deadline);
        }else{
            $this->deadline = $deadline;
        }
    }
}
