<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\{BoardController, TaskController, SubtaskController};

Route::prefix('v1')->group(function () {
    Route::prefix('board')->name('board.')->group(function () {
        Route::get('', [BoardController::class, 'list'])->name('list');
        Route::get('{boardId}', [BoardController::class, 'show'])->name('show');
        Route::post('', [BoardController::class, 'create'])->name('create');
    });

    Route::prefix('task')->name('task.')->group(function () {
        Route::get('', [TaskController::class, 'list'])->name('list');
        Route::post('', [TaskController::class, 'add'])->name('add');
        Route::get('{task}', [TaskController::class, 'show'])->name('show');
        Route::get('{task}/status-priority-fields', [TaskController::class, 'showWithStatusPriorityFields'])
            ->name('showWithStatusPriorityFields');
        Route::get('{task}/start', [TaskController::class, 'start'])->name('start');
        Route::get('{task}/completed', [TaskController::class, 'completed'])->name('completed');
        Route::get('{task}/reopen', [TaskController::class, 'reopen'])->name('reopen');
        Route::post('priority', [TaskController::class, 'changePriority'])->name('priority');
        Route::post('deadline', [TaskController::class, 'changeDeadline'])->name('deadline');
    });

    Route::prefix('subtask')->name('subtask.')->group(function () {
        Route::get('', [SubtaskController::class, 'list'])->name('list');
        Route::post('', [SubtaskController::class, 'add'])->name('add');
    });


});
