<?php

namespace App\Tools;

use Exception;

Trait ExceptionsTrait
{
    /**
     * @throws Exception
     */
    protected function throwException(?string $message = null)
    {
        throw new Exception($message);
    }
}
