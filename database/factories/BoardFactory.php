<?php

namespace Database\Factories;

use App\Entities\Board;
use App\ValueObjects\Board\BoardDescription;
use App\ValueObjects\Board\BoardName;
use Doctrine\ORM\EntityManagerInterface;

class BoardFactory
{
    public static function make(array $overrides = []): Board
    {
        $description = fake()->optional()->text(200);
        return new Board(
            existsByUserIdAndName: 0,
            name: new BoardName(fake()->unique()->words(3, true)),
            userId: 1,
            description: $description ? new BoardDescription($description) : null,
        );
    }

    public static function create(EntityManagerInterface $em, array $overrides = []): Board
    {
        $board = self::make($overrides);
        $em->persist($board);
        $em->flush();
        return $board;
    }

    public static function createWithCount(EntityManagerInterface $em, int $count = 1, array $overrides = []): array
    {
        if(!$count){
            $count = 1;
        }
        $boards = [];
        foreach (range(1, $count) as $i) {
            $boards[] = self::create($em, $overrides);
        }
        return $boards;
    }
}
