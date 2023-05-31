<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanAccomplishmentStoreRequest extends FormRequest
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
            'suitable_kpi_id' => ['required', 'exists:suitable_kpis,id'],
            'reporting_period_id' => [
                'required',
                'exists:reporting_periods,id',
            ],
            'plan_value' => ['required', 'numeric'],
            'accom_value' => ['required', 'numeric'],
            'plan_status' => ['required', 'max:255'],
            'accom_status' => ['required', 'max:255'],
        ];
    }
}
