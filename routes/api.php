<?php

use Illuminate\Support\Facades\Route;
use Src\infrastructure\DeliveryMechanism\Http\Api\V1\Controllers\{BoardController, SubtaskController, TaskController};

Route::prefix('v1')->group(function () {
    Route::prefix('board')->name('board.')->group(function () {
        Route::get('', [BoardController::class, 'list'])->name('list');
        Route::get('paginate', [BoardController::class, 'paginate'])->name('paginate');
        Route::get('{boardId}', [BoardController::class, 'show'])->name('show');
        Route::post('', [BoardController::class, 'create'])->name('create');
    });

    Route::prefix('task')->name('task.')->group(function () {
        Route::get('', [TaskController::class, 'list'])->name('list');
        Route::get('paginate', [TaskController::class, 'paginate'])->name('paginate');
        Route::post('', [TaskController::class, 'add'])->name('add');
        Route::get('{task}', [TaskController::class, 'show'])->name('show');
        Route::get('{task}/start', [TaskController::class, 'start'])->name('start');
        Route::get('{task}/complete', [TaskController::class, 'complete'])->name('complete');
        Route::get('{task}/reopen', [TaskController::class, 'reopen'])->name('reopen');
        Route::post('prioritize', [TaskController::class, 'prioritize'])->name('prioritize');
        Route::post('deadline', [TaskController::class, 'changeDeadline'])->name('deadline');

        Route::prefix('{task_id}/subtask')->name('subtask.')->group(function () {
            Route::get('', [SubtaskController::class, 'list'])->name('list');
            Route::post('', [SubtaskController::class, 'add'])->name('add');
            Route::get('{subtask}/start', [SubtaskController::class, 'start'])->name('start');
            Route::get('{subtask}/complete', [SubtaskController::class, 'complete'])->name('complete');
            Route::get('{subtask}/reopen', [SubtaskController::class, 'reopen'])->name('reopen');
        });
    });




});
