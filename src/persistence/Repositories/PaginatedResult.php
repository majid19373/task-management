<?php

namespace Src\persistence\Repositories;

use Illuminate\Support\Collection;

final class PaginatedResult
{

    public function __construct(public array $list, public array $paginator)
    {}

    public static function make(array $list, array $paginator): PaginatedResult
    {
        return new PaginatedResult($list, $paginator);
    }
}
