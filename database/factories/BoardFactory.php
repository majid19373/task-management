<?php
/** @var Factory $factory */

use App\Entities\Board;
use App\ValueObjects\Board\BoardDescription;
use App\ValueObjects\Board\BoardName;
use LaravelDoctrine\ORM\Testing\Factory;
use Faker\Generator;

$factory->define(Board::class, function (Generator $faker) {
    $description = $faker->optional()->text(200);
    return [
        'name' => new BoardName($faker->unique()->words(3, true)),
        'userId' => 1,
        'description' => $description ? new BoardDescription($description) : null,
    ];
});
