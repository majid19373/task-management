<?php

namespace App\Exceptions;

use App\Tools\Responses;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    use Responses;

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): JsonResponse
    {
        logger()->error($e->getCode());
        return $this->respondException($e->getMessage());
    }
}
