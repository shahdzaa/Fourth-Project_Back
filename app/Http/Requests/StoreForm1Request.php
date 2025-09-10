<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // لا تنسَ إضافة هذا السطر

class StoreForm1Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // يمكنك إضافة منطق الصلاحيات هنا إذا أردت
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // --- 1. تعريف القواعد الأساسية المشتركة بين الإنشاء والتحديث ---
        $rules = [
            // بيانات البناء (بدون البادئة 'building.')
            'is_legal' => 'required|boolean',
            'number_of_floors' => 'required|integer|min:0',
            'number_of_floors_violating' => 'required|integer|min:0',
            'structural_pattern' => ['required', Rule::in(['إطار بيتوني', 'جدران بيتونية', 'حجري', 'خشبي', 'مختلط'])],
            'number_of_families_before_departure' => 'required|integer|min:0',
            'number_of_families_after_departure' => 'required|integer|min:0',
            'level_of_damage' => ['required', Rule::in(['0', '1', '2', '3', '4'])], // الأفضل معاملتها كنصوص
            'is_materials_from_the_neighborhood' => 'required|boolean',
            'neighbourhood_id' => 'required|exists:neighbourhoods,id',

            // بيانات تقارير الضرر
            'damage_reports' => 'required|array|min:1',
            'damage_reports.*.photo' => 'nullable|string|max:500',
            'damage_reports.*.degree_of_damage' => ['required', Rule::in(['0', '1', '2', '3', '4'])],
            'damage_reports.*.report_number' => 'required|', // يمكن أن يكون أي نص
            'damage_reports.*.foundation_id' => 'required|exists:foundations,id',
            'damage_reports.*.committee_id' => 'required|exists:committees,id',
        ];

        // --- 2. إضافة قواعد خاصة بعملية التحديث فقط ---
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // عند التحديث، يجب أن يكون لكل تقرير ضرر 'id' موجود مسبقاً في قاعدة البيانات
            $rules['damage_reports.*.id'] = 'required|exists:damage_reports,id';
        }

        return $rules;
    }

    /**
     * ✅ إضافة: دمج البيانات قبل التحقق منها
     * هذه الدالة ستقوم بدمج مصفوفة 'building' مع بقية الحقول
     * لتتوافق مع دالة update في الكونترولر.
     */
    protected function prepareForValidation()
    {
        // نتأكد من وجود مصفوفة 'building' وأنها بالفعل مصفوفة
        if ($this->has('building') && is_array($this->building)) {
            // نقوم بدمج محتويات 'building' مع الطلب الرئيسي
            $this->merge($this->building);
        }
    }
}
