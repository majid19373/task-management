<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\{BoardController, TaskController};

Route::prefix('v1')->group(function () {
    Route::prefix('board')->name('board.')->group(function () {
        Route::get('', [BoardController::class, 'getList'])->name('list');
        Route::get('{boardId}', [BoardController::class, 'show'])->name('show');
        Route::post('', [BoardController::class, 'create'])->name('create');
    });

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
