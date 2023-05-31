<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KeyPeformanceIndicatorStoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'weight' => ['required', 'numeric'],
            'objective_id' => ['required', 'exists:objectives,id'],
            'strategy_id' => ['required', 'exists:strategies,id'],
            'created_by_id' => ['required', 'exists:users,id'],
            'reporting_period_type_id' => [
                'required',
                'exists:reporting_period_types,id',
            ],
        ];
    }
}
