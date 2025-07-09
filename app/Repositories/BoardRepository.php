<?php

namespace App\Repositories;

use App\Models\Board;

final class BoardRepository extends BaseRepository
{
    public function __construct(){
        $this->model = new Board();
    }
}
