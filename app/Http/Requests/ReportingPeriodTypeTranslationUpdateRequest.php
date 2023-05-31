<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportingPeriodTypeTranslationUpdateRequest extends FormRequest
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
            'reporting_period_type_id' => [
                'required',
                'exists:reporting_period_types,id',
            ],
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
        ];
    }
}
