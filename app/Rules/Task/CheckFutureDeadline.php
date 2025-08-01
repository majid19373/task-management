<?php

namespace App\Rules\Task;

use Carbon\Carbon;
use InvalidArgumentException;

final class CheckFutureDeadline
{
    /**
     * @throws InvalidArgumentException
     */
    public function validate(?string $deadline): void
    {
        if($deadline){
            $deadline = Carbon::make($deadline);
            if (!$deadline->isFuture()) {
                throw new InvalidArgumentException(
                    message: 'The deadline field must be a valid date',
                );
            }
        }
    }
}
