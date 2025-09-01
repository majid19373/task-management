<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Resources\Board;

use Src\Domain\Board\Board;
use Illuminate\Support\Collection;

final class BoardResource
{
    public const array JSON_STRUCTURE = [
        'id',
        'name',
        'description',
    ];

    public static function toArray(Board $board): array
    {
        return [
            'id' => $board->getId(),
            'name' => $board->getName()->value(),
            'description' => $board->getDescription()?->value(),
        ];
    }

    public static function toArrayList(array $boards): Collection
    {
        return collect($boards)->map(function ($board) {
            return BoardResource::toArray($board);
        });
    }
}
