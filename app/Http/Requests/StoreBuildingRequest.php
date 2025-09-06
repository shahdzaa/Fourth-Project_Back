<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class StoreBuildingRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'degree_of_damage' => 'nullable|numeric|min:0|max:100',
            'external_id' => 'nullable|string|max:255',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules = [
                'name' => 'sometimes|string|max:255',
                'degree_of_damage' => 'sometimes|nullable|numeric|min:0|max:100',
                'external_id' => 'sometimes|nullable|string|max:255',
            ];
        }
        return $rules;
    }
}
