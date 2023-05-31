<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PerspectiveUpdateRequest extends FormRequest
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
            'created_by_id' => ['required', 'exists:users,id'],
            'updated_by_id' => ['required', 'exists:users,id'],
        ];
    }
}
