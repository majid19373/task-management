<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected const array BASE_HEADERS = [
        'Accept' => 'application/json',
    ];

    protected const array MAIN_JSON_STRUCTURE = [
        'message',
        'status',
        'status_code',
    ];

    protected const array PAGINATOR_JSON_STRUCTURE = [
        'total',
        'current_page',
        'limit',
    ];

    protected function makeMainJsonStructure(mixed $data): array
    {
        return [
            'data' => $data,
            ...self::MAIN_JSON_STRUCTURE,
        ];
    }

    protected function makeMainJsonStructureWithoutData(): array
    {
        return [
            'data',
            ...self::MAIN_JSON_STRUCTURE,
        ];
    }

    protected function makeListMainJsonStructure(mixed $data): array
    {
        return [
            'data' => [
                '*' => $data
            ],
            ...self::MAIN_JSON_STRUCTURE,
        ];
    }

    protected function makePaginatorResponseStructure(mixed $data): array
    {
        return [
            'data' => [
                '*' => $data
            ],
            'paginator' => self::PAGINATOR_JSON_STRUCTURE,
            ...self::MAIN_JSON_STRUCTURE,
        ];
    }
}
