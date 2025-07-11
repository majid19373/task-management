<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\{BoardController, TaskController};

Route::prefix('v1')->group(function () {
    Route::resource('board', BoardController::class)
        ->names('board')
        ->only(['index', 'show', 'store']);

    Route::prefix('task')->name('task.')->group(function () {
        Route::get('{task}/start', [TaskController::class, 'start'])->name('start');
        Route::get('{task}/completed', [TaskController::class, 'completed'])->name('completed');
        Route::get('{task}/reopen', [TaskController::class, 'reopen'])->name('reopen');
        Route::post('priority', [TaskController::class, 'priority'])->name('priority');
        Route::post('deadline', [TaskController::class, 'deadline'])->name('deadline');

        Route::resource('/', TaskController::class)
            ->parameters(['' => 'task'])
            ->only(['index', 'show', 'store', 'edit']);
    });


});
