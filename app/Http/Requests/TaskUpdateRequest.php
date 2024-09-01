<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Statuses;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'sometimes',
                'string',
                'min:1',
                'max:255',
            ],
            'description' => [
                'sometimes',
                'string',
                'min:1',
                'max:65535',
            ],
            'team_id' => [
                'sometimes',
                'integer',
                'exists:teams,id'
            ],
            'status' => [
                'sometimes',
                'required',
                'string',
                Rule::enum(Statuses::class),
            ],
        ];
    }
}
