<?php

namespace App\Repositories;

use App\Models\Task;

final class TaskRepository extends BaseRepository
{
    public function __construct(
        Task $task
    ){
        $this->model = $task;
    }
}
