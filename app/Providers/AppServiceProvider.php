<?php

namespace App\Providers;

use Src\persistence\Repositories\Task\{TaskRepository, TaskRepositoryInterface};
use Src\persistence\Repositories\Board\{BoardRepository, BoardRepositoryInterface};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->bindDependencies();
    }

    /**
     * Bootstrap any application services.
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
