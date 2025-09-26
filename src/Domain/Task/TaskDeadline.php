<?php

namespace Src\Domain\Task;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\{Column, Embeddable};
use DomainException;

#[Embeddable]
final class TaskDeadline
{
    #[Column(name: "deadline", type: 'string', nullable: true)]
    private DateTimeImmutable $value;

    public function __construct(string $value, DateTimeImmutable $currentDate)
    {
        $value = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value);
        if(!$value){
            throw new DomainException('The deadline field must be a valid date.');
        }
        if($currentDate > $value){
            throw new DomainException('The deadline date must be greater than the current date.');
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
