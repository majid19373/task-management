<?php

namespace Src\infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

final class PriorityTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer'],
            'priority' => ['required'],
        ];
    }
}
