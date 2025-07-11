<?php

namespace App\DTO\Task;

use App\DTO\BaseDTO;
use Carbon\Carbon;

final class TaskDTO extends BaseDTO
{
    public function __construct(
        public string|int|null $id = parent::NOT_PERSISTED,
        public string|int|null $board_id = parent::NOT_PERSISTED,
        public ?string $title = parent::NOT_PERSISTED,
        public ?string $description = parent::NOT_PERSISTED,
        public ?string $status = parent::NOT_PERSISTED,
        public ?string $priority = parent::NOT_PERSISTED,
        public Carbon|string|null $deadline = parent::NOT_PERSISTED,
    )
    {}
}
