<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\{BoardController};

Route::prefix('v1')->group(function () {
    Route::resource('board', BoardController::class)
        ->names('board')
        ->only(['index', 'show', 'store']);
});
