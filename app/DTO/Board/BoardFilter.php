<?php

namespace App\DTO\Board;

final class BoardFilter
{
    public function __construct(
        public bool $isPaginated = true,
        public ?int $page = 0,
        public ?int $perPage = 10,
    )
    {}
}
