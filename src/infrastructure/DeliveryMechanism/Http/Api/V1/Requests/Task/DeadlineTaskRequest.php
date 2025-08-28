<?php

namespace Src\infrastructure\DeliveryMechanism\Http\Api\V1\Requests\Task;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
}
