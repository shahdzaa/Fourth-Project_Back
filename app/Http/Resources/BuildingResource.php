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
        return $rsources;
    }
}
