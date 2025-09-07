<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreForm2Request extends FormRequest
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
    public function rules()
    {
        return [
            'damage_report.degree_of_damage' => 'required|in:0,1,2,3,4',
            'damage_report.report_number' => 'required|in:2',
            'damage_report.foundation_id' => 'required|exists:foundations,id',
            'damage_report.committee_id' => 'required|exists:committees,id',
            'damage_report.photo' => 'nullable|string|max:500',

            'building.name' => 'nullable|string|max:255',
            'building.level_of_damage' => 'required|in:0,1,2,3,4',
            'building.is_materials_from_the_neighborhood' => 'required|boolean',
            'building.neighbourhood_id' => 'required|exists:neighbourhoods,id',
        ];
    }
}
