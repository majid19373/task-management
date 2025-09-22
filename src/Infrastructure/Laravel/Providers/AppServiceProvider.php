<?php

namespace Src\Infrastructure\Laravel\Providers;

use Src\Application\Bus\Command\{CachingMappingCommandsProvider,
    CommandBus,
    LaravelCommandBus,
    MappingCommandsProvider};
use Src\Application\Bus\Query\{CachingMappingQueriesProvider, LaravelQueryBus, MappingQueriesProvider, QueryBus};
use Src\Application\CommandHandlers\Board\{CreateBoardCommandHandler};
use Src\Application\CommandHandlers\Subtask\{AddSubtaskCommandHandler,
    CompeteSubtaskCommandHandler,
    RemoveSubtaskCommandHandler,
    ReopenSubtaskCommandHandler,
    StartSubtaskCommandHandler};
use Src\Application\CommandHandlers\Task\{AddTaskCommandHandler,
    ChangeDeadlineTaskCommandHandler,
    CompleteTaskCommandHandler,
    PrioritizeTaskCommandHandler,
    ReopenTaskCommandHandler,
    StartTaskCommandHandler};
use Src\Application\Repositories\{BoardRepositoryInterface, TaskRepositoryInterface};
use Src\Application\QueryHandlers\Board\{GetBoardQueryHandler, ListBoardQueryHandler, PaginatedListBoardQueryHandler};
use Src\Application\QueryHandlers\Subtask\{ListSubtaskQueryHandler};
use Src\Application\QueryHandlers\Task\{FindTaskQueryHandler, ListTaskQueryHandler, PaginateTaskQueryHandler};
use Src\Infrastructure\Persistence\Repositories\Task\{TaskRepository};
use Src\Infrastructure\Persistence\Repositories\Board\{BoardRepository};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->bindQueryDependencies();
        $this->bindCommandDependencies();
        $this->bindRepositoryDependencies();
    }

    public function boot(): void
    {
        //
    }

    private function bindQueryDependencies(): void
    {
        $this->app->tag(
            [
                GetBoardQueryHandler::class,
                ListBoardQueryHandler::class,
                PaginatedListBoardQueryHandler::class,
                FindTaskQueryHandler::class,
                ListTaskQueryHandler::class,
                PaginateTaskQueryHandler::class,
                ListSubtaskQueryHandler::class,
            ],
            'query_handler'
        );

        $this->app->singleton(QueryBus::class, function ($app) {
            if(config('app.env') === 'production'){
                $provider = new CachingMappingQueriesProvider();
            }else{
                $provider = new MappingQueriesProvider();
            }
            return new LaravelQueryBus($provider->provide($app->tagged('query_handler')));
        });
    }

    private function bindCommandDependencies(): void
    {
        $this->app->tag(
            [
                CreateBoardCommandHandler::class,
                AddTaskCommandHandler::class,
                ChangeDeadlineTaskCommandHandler::class,
                CompleteTaskCommandHandler::class,
                PrioritizeTaskCommandHandler::class,
                ReopenTaskCommandHandler::class,
                StartTaskCommandHandler::class,
                AddSubtaskCommandHandler::class,
                CompeteSubtaskCommandHandler::class,
                ReopenSubtaskCommandHandler::class,
                StartSubtaskCommandHandler::class,
                RemoveSubtaskCommandHandler::class,
            ],
            'command_handler'
        );

        $this->app->singleton(CommandBus::class, function ($app) {
            if(config('app.env') === 'production'){
                $provider = new CachingMappingCommandsProvider();
            }else{
                $provider = new MappingCommandsProvider();
            }
            return new LaravelCommandBus($provider->provide($app->tagged('command_handler')));
        });
    }

    private function bindRepositoryDependencies(): void
    {
        $this->app->singleton(
            BoardRepositoryInterface::class,
            BoardRepository::class
        );

        $this->app->singleton(
            TaskRepositoryInterface::class,
            TaskRepository::class
        );
    }
}
