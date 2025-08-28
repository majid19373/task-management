<?php
/** @var Factory $factory */

use Src\domain\Entities\Subtask\Subtask;
use Src\domain\Entities\Task\Task;
use Src\domain\Entities\Subtask\ValueObjects\SubtaskDescription;
use Src\domain\Entities\Subtask\ValueObjects\SubtaskStatus;
use Src\domain\Entities\Subtask\ValueObjects\SubtaskTitle;
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
