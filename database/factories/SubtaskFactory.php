<?php
/** @var Factory $factory */

use Src\Domain\Subtask\Subtask;
use Src\Domain\Task\Task;
use Src\Domain\Subtask\SubtaskDescription;
use Src\Domain\Subtask\SubtaskStatus;
use Src\Domain\Subtask\SubtaskTitle;
use LaravelDoctrine\ORM\Testing\Factory;
use Faker\Generator;

$factory->define(Subtask::class, function (Generator $faker, array $attributes) {
    $description = $attributes['description'] ?? $faker->optional()->text(500);
    return [
        'id' => Str::ulid(),
        'task' => $attributes['task'] ?? entity(Task::class)->create(),
        'title' => $attributes['title'] ?? new SubtaskTitle($faker->unique()->words(5, true)),
        'description' => $attributes['description'] ?? $description ? new SubtaskDescription($description) : null,
        'status' => $attributes['status'] ?? SubtaskStatus::NOT_STARTED,
    ];
});
