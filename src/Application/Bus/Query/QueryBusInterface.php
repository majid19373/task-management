<?php

namespace Src\Application\Bus\Query;

interface QueryBusInterface
{
    public function ask(object $query): mixed;
}
