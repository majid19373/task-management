<?php

namespace App\ValueObjects\Task;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\{Column, Embeddable};
use DomainException;

#[Embeddable]
final class TaskDeadline
{
    #[Column(name: "deadline", type: 'string')]
    private DateTimeImmutable $value;

    public function __construct(string $value, DateTimeImmutable $currentDate)
    {
        if(!$value){
            throw new DomainException('The deadline field must be a valid date');
        }
        $value = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value);
        if($currentDate > $value){
            throw new DomainException('The deadline field must be a valid date');
        }
        $this->value = $value;
    }

    public function value(): DateTimeImmutable
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d H:i:s');
    }
}
