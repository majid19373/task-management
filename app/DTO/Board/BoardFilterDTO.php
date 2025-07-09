<?php

namespace App\DTO\Board;

use App\DTO\BaseDTO;

final class BoardFilterDTO extends BaseDTO
{
    public function __construct(
        public bool $is_paginated = true,
        public int $per_page = 10,
    )
    {}
}
