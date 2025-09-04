<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Building extends Model
{
    use HasFactory;
    public function neighbourhood()
    {
        return $this->belongsTo(Neighbourhood::class);
    }
    public function damageReports()
    {
        return $this->hasMany(DamageReport::class);
    }
    protected $fillable = [
        'name',
        'building',
        'external_id',
        'is_legal',
        'number_of_floors',
        'number_of_floors_violating',
        'structural_pattern',
        'number_of_families_before_departure',
        'number_of_families_after_departure',
        'level_of_damage',
        'neighbourhood_id',
        'is_materials_from_the_neighborhood',
    ];


}
