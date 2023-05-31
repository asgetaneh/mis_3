<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuitableKpiUpdateRequest extends FormRequest
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
            'key_peformance_indicator_id' => [
                'required',
                'exists:key_peformance_indicators,id',
            ],
            'office_id' => ['required', 'exists:offices,id'],
            'planing_year_id' => ['required', 'exists:planing_years,id'],
        ];
    }
}
