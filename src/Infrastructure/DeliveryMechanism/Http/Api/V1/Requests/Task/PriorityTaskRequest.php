<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Src\Application\Commands\Task\PrioritizeTaskCommand;

final class PriorityTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required'],
            'priority' => ['required'],
        ];
    }

    public function makeDTO(): PrioritizeTaskCommand
    {
        return new PrioritizeTaskCommand(
            id: $this->id,
            priority: $this->priority,
        );
    }
}
