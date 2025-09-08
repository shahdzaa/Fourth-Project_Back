<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingF2Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $rsources= [
            'name' => $this->name,
            'level_of_damage' => $this->level_of_damage,
            'is_materials_from_the_neighborhood' => $this->is_materials_from_the_neighborhood,
            'neighbourhood_id'=>$this->neighbourhood_id
        ];
        return $rsources;
    }
}
