<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreForm1Request extends FormRequest
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
            // بيانات البناء
            'building.name' => 'nullable|string|max:255',
            'building.is_legal' => 'required|boolean',
            'building.number_of_floors' => 'required|integer|min:0',
            'building.number_of_floors_violating' => 'required|integer|min:0',
            'building.type' => 'required|in:مستشفى,مدرسة,بناء سكني,جامع,كنيسة',
            'building.structural_pattern' => 'required|in:إطار بيتوني,جدران بيتونية,حجري,خشبي,مختلط',
            'building.number_of_families_before_departure' => 'required|integer|min:0',
            'building.number_of_families_after_departure' => 'required|integer|min:0',
            'building.level_of_damage' => 'required|in:0,1,2,3,4',
            'building.is_materials_from_the_neighborhood' => 'required|boolean',
            'building.neighbourhood_id' => 'required|exists:neighbourhoods,id',

            // بيانات تقارير الضرر
            'damage_reports' => 'required|array|min:1',
            'damage_reports.*.photo' => 'nullable|string|max:500',
            'damage_reports.*.degree_of_damage' => 'required|in:0,1,2,3,4',
            'damage_reports.*.report_number' => 'required|in:1',
            'damage_reports.*.foundation_id' => 'required|exists:foundations,id',
            'damage_reports.*.committee_id' => 'required|exists:committees,id',
        ];
    }
}
