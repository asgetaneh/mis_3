<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoalStoreRequest extends FormRequest
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
            'is_active' => ['required', 'boolean'],
            'created_by_id' => ['required', 'max:255'],
        ];
    }
}
