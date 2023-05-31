<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StrategyUpdateRequest extends FormRequest
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
            'objective_id' => ['required', 'exists:objectives,id'],
            'created_by_id' => ['required', 'exists:users,id'],
            'updated_by_id' => ['required', 'exists:users,id'],
        ];
    }
}
