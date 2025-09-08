<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreForm2Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            // --- قواعد التحقق لحقول البناء ---
            'building' => 'required|array',
            'building.name' => 'nullable|string|max:255',
            'building.level_of_damage' => ['required', Rule::in(['0', '1', '2', '3', '4'])],
            'building.is_materials_from_the_neighborhood' => 'required|boolean',
            'building.neighbourhood_id' => 'required|exists:neighbourhoods,id',

            // --- قواعد التحقق لحقول تقرير الضرر ---
            'damage_report' => 'required|array',
            'damage_report.degree_of_damage' => ['required', Rule::in(['0', '1', '2', '3', '4'])],
            'damage_report.report_number' => ['required', Rule::in(['2'])],
            'damage_report.foundation_id' => 'required|exists:foundations,id',
            'damage_report.committee_id' => 'required|exists:committees,id',
            'damage_report.photo' => 'nullable|string|max:500',
        ];

        // عند التحديث (PUT/PATCH)، يجب أن يكون لتقرير الضرر 'id' موجود مسبقاً
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['damage_report.id'] = 'required|exists:damage_reports,id';
        }

        return $rules;
    }

    // لا حاجة لدالة prepareForValidation هنا لأننا سنتعامل مع البيانات المتداخلة مباشرة
}
