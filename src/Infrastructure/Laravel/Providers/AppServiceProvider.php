<?php

namespace Src\Infrastructure\Laravel\Providers;

use Src\Infrastructure\Persistence\Repositories\Board\BoardRepositoryInterface;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepository;
use Src\Infrastructure\Persistence\Repositories\Task\{TaskRepositoryInterface};
use Src\Infrastructure\Persistence\Repositories\Board\{BoardRepository};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any Application services.
     */
    public function register(): void
    {
        $this->bindDependencies();
    }

    /**
     * Bootstrap any Application services.
     */
    public function boot(): void
    {
        //
    }

    private function bindDependencies(): void
    {
        $this->app->bind(
            BoardRepositoryInterface::class,
            BoardRepository::class
        );

        $this->app->bind(
            TaskRepositoryInterface::class,
            TaskRepository::class
        );
    }
}
