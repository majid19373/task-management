<?php

namespace Tests;

use App\Entities\Board;
use App\Entities\Task;
use Database\Factories\BoardFactory;
use Database\Factories\TaskFactory;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Doctrine\ORM\Tools\SchemaTool;
use LaravelDoctrine\ORM\Testing\Factory;
use Faker\Generator;

abstract class TestCase extends BaseTestCase
{
    protected EntityManagerInterface $em;
    protected static bool $factoriesRegistered = false;

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

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->em = $this->app->make(EntityManagerInterface::class);
        $metaData = $this->em->getMetadataFactory()->getAllMetadata();

        if (!empty($metaData)) {
            $tool = new SchemaTool($this->em);
            $tool->dropSchema($metaData);
            $tool->createSchema($metaData);
        }
    }

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
