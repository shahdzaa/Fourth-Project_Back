<?php

namespace App\Http\Resources;

use App\Models\DamageReport;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $rsources= [
            'id' => $this->id,
            'name' => $this->name,
            'level_of_damage' => $this->level_of_damage,
            'external_id' => $this->external_id,
        ];
        if ($this->isMethod('show')){
            $rsources= [
                'id' => $this->id,
                'name' => $this->name,
                'level_of_damage' => $this->level_of_damage,
                'is_legal'=>$this->is_legal,
                'number_of_floors'=>$this->number_of_floors,
                'number_of_floors_violating'=>$this->number_of_floors_violating,
                'structural_pattern'=>$this->structural_pattern,
                'number_of_families_before_departure'=>$this->number_of_families_before_departure,
                'number_of_families_after_departure'=>$this->number_of_families_after_departure,
                'neighbourhood_name'=>$this->neighbourhood->name,
                'is_materials_from_the_neighborhood'=>$this->is_materials_from_the_neighborhood,
            ];
        }
        return $rsources;
    }
}
