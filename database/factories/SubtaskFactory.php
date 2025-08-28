<?php
/** @var Factory $factory */

use App\Entities\Subtask;
use App\Entities\Task;
use App\ValueObjects\Subtask\SubtaskDescription;
use App\ValueObjects\Subtask\SubtaskStatus;
use App\ValueObjects\Subtask\SubtaskTitle;
use LaravelDoctrine\ORM\Testing\Factory;
use Faker\Generator;

$factory->define(Subtask::class, function (Generator $faker) {
    $description = $faker->optional()->text(500);
    return [
        'task' => entity(Task::class)->create(),
        'title' => new SubtaskTitle($faker->unique()->words(5, true)),
        'description' => $description ? new SubtaskDescription($description) : null,
        'status' => SubtaskStatus::NOT_STARTED,
    ];
});
