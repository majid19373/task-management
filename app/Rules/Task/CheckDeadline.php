<?php

namespace App\Rules\Task;

use App\Tools\ExceptionsTrait;
use Carbon\Carbon;
use Exception;

final class CheckDeadline
{
    use ExceptionsTrait;

    /**
     * @throws Exception
     */
    public function validate(?Carbon $deadline): void
    {
        if ($deadline && $deadline->diffInMinutes(Carbon::now()) > 1) {
            $this->throwException(
                message: 'The deadline field must be a valid date',
            );
        }
    }
}
