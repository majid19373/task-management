<?php

namespace App\DTO\Board;

final class BoardFilterDTO
{
    public function __construct(
        public bool $isPaginated = true,
        public ?int $perPage = 10,
    )
    {}
}
