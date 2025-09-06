<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DamageReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, // دائمًا من الجيد إرجاع الـ ID
            'report_number' => $this->report_number,
            'degree_of_damage' => $this->degree_of_damage,
            'image_url' => $this->photo
                ? Storage::url($this->photo)
                : null,
            'building_name' => $this->building->name, // جلب الاسم من علاقة البناية
            'foundation_name' => $this->foundation->name, // جلب الاسم من علاقة المؤسسة
            'created_at'=>$this->created_at,
            'committee_number' => $this->committee->id,
            // يمكنك الاحتفاظ بالـ ID إذا احتجته في الواجهة الأمامية
            'building_id' => $this->building_id,
            'foundation_id' => $this->foundation_id,
             ];
    }
}
