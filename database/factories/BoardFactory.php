<?php
/** @var Factory $factory */

use Src\Domain\Board\Board;
use Src\Domain\Board\BoardDescription;
use Src\Domain\Board\BoardName;
use LaravelDoctrine\ORM\Testing\Factory;
use Faker\Generator;

$factory->define(Board::class, function (Generator $faker, array $attributes) {
    $description = $attributes['description'] ?? $faker->optional()->text(200);
    return [
        'name' => $attributes['name'] ?? new BoardName($faker->unique()->words(3, true)),
        'userId' => $attributes['userId'] ?? 1,
        'description' => $description ? new BoardDescription($description) : null,
    ];
});
