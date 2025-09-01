<?php
/** @var Factory $factory */

use Src\Domain\Board\Board;
use Src\Domain\Task\Task;
use Src\Domain\Task\TaskDeadline;
use Src\Domain\Task\TaskDescription;
use Src\Domain\Task\TaskPriority;
use Src\Domain\Task\TaskStatus;
use Src\Domain\Task\TaskTitle;
use LaravelDoctrine\ORM\Testing\Factory;
use Faker\Generator;

$factory->define(Task::class, function (Generator $faker) {
    $description = $faker->optional()->text(500);
    $deadline = $overrides['deadline'] ?? fake()->optional()->dateTimeBetween('+1 day', '+7 days');
    $deadline = $deadline?->format('Y-m-d H:i:s');
    return [
        'board' => entity(Board::class)->create(),
        'title' => new TaskTitle($faker->unique()->words(5, true)),
        'description' => $description ? new TaskDescription($description) : null,
        'deadline' => $deadline ? new TaskDeadline($deadline, new DateTimeImmutable()) : null,
        'status' => TaskStatus::NOT_STARTED,
        'priority' => TaskPriority::MEDIUM,
    ];
});
