<?php

namespace Src\Infrastructure\Laravel\Providers;

use Src\Application\Bus\CommandBus;
use Src\Application\Bus\QueryBus;
use Src\Application\CommandHandlers\Board\CreateBoardCommandHandler;
use Src\Application\CommandHandlers\Subtask\AddSubtaskCommandHandler;
use Src\Application\CommandHandlers\Subtask\CompeteSubtaskCommandHandler;
use Src\Application\CommandHandlers\Subtask\ReopenSubtaskCommandHandler;
use Src\Application\CommandHandlers\Subtask\StartSubtaskCommandHandler;
use Src\Application\CommandHandlers\Task\AddTaskCommandHandler;
use Src\Application\CommandHandlers\Task\ChangeDeadlineTaskCommandHandler;
use Src\Application\CommandHandlers\Task\CompleteTaskCommandHandler;
use Src\Application\CommandHandlers\Task\PrioritizeTaskCommandHandler;
use Src\Application\CommandHandlers\Task\ReopenTaskCommandHandler;
use Src\Application\CommandHandlers\Task\StartTaskCommandHandler;
use Src\Application\Contracts\Repositories\BoardRepositoryInterface;
use Src\Application\QueryHandlers\Board\GetBoardQueryHandler;
use Src\Application\QueryHandlers\Board\ListBoardQueryHandler;
use Src\Application\QueryHandlers\Board\PaginatedListBoardQueryHandler;
use Src\Application\QueryHandlers\Subtask\ListSubtaskQueryHandler;
use Src\Application\QueryHandlers\Task\FindTaskQueryHandler;
use Src\Application\QueryHandlers\Task\ListTaskQueryHandler;
use Src\Application\QueryHandlers\Task\PaginateTaskQueryHandler;
use Src\Infrastructure\Persistence\Repositories\Task\TaskRepository;
use Src\Application\Contracts\Repositories\{TaskRepositoryInterface};
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
        $this->app->tag(GetBoardQueryHandler::class, 'query_handler');
        $this->app->tag(ListBoardQueryHandler::class, 'query_handler');
        $this->app->tag(PaginatedListBoardQueryHandler::class, 'query_handler');

        $this->app->tag(FindTaskQueryHandler::class, 'query_handler');
        $this->app->tag(ListTaskQueryHandler::class, 'query_handler');
        $this->app->tag(PaginateTaskQueryHandler::class, 'query_handler');

        $this->app->tag(ListSubtaskQueryHandler::class, 'query_handler');

        $this->app->singleton(QueryBus::class, function ($app) {
            return new QueryBus($app->tagged('query_handler'));
        });
    }

    private function bindCommandDependencies(): void
    {
        $this->app->tag(CreateBoardCommandHandler::class, 'command_handler');

        $this->app->tag(AddTaskCommandHandler::class, 'command_handler');
        $this->app->tag(ChangeDeadlineTaskCommandHandler::class, 'command_handler');
        $this->app->tag(CompleteTaskCommandHandler::class, 'command_handler');
        $this->app->tag(PrioritizeTaskCommandHandler::class, 'command_handler');
        $this->app->tag(ReopenTaskCommandHandler::class, 'command_handler');
        $this->app->tag(StartTaskCommandHandler::class, 'command_handler');

        $this->app->tag(AddSubtaskCommandHandler::class, 'command_handler');
        $this->app->tag(CompeteSubtaskCommandHandler::class, 'command_handler');
        $this->app->tag(ReopenSubtaskCommandHandler::class, 'command_handler');
        $this->app->tag(StartSubtaskCommandHandler::class, 'command_handler');

        $this->app->singleton(CommandBus::class, function ($app) {
            return new CommandBus($app->tagged('command_handler'));
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
