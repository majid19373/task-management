<?php

namespace Src\Infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Src\Application\Commands\Task\ChangeDeadlineTaskCommand;

final class DeadlineTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer'],
            'deadline' => ['required', 'date'],
        ];
    }

    public function makeDTO(): ChangeDeadlineTaskCommand
    {
        return new ChangeDeadlineTaskCommand(
            id: $this->id,
            deadline: $this->deadline,
        );
    }
}
