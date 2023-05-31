<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportingPeriodUpdateRequest extends FormRequest
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
            'planing_year_id' => ['required', 'exists:planing_years,id'],
            'start_date' => ['required', 'max:255', 'string'],
            'end_date' => ['required', 'max:255', 'string'],
            'reporting_period_type_id' => [
                'required',
                'exists:reporting_period_types,id',
            ],
        ];
    }
}
