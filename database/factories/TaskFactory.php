<?php
/** @var Factory $factory */

use Src\domain\Entities\Board\Board;
use Src\domain\Entities\Task\Task;
use Src\domain\Entities\Task\ValueObjects\TaskDeadline;
use Src\domain\Entities\Task\ValueObjects\TaskDescription;
use Src\domain\Entities\Task\ValueObjects\TaskPriority;
use Src\domain\Entities\Task\ValueObjects\TaskStatus;
use Src\domain\Entities\Task\ValueObjects\TaskTitle;
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
