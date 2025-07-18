<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

final class PaginatedResult
{

    public function __construct(public Collection $list, public array $paginator)
    {}

    public static function make(Collection $list, array $paginator): PaginatedResult
    {
        return new PaginatedResult($list, $paginator);
    }
}
