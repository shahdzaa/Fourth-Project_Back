<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class DamageReport extends Model
{
    use HasFactory;

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
    public function foundation()
    {
        return $this->belongsTo(Foundation::class);
    }
    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }
    protected $fillable=['photo','degree_of_damage','report_number','building_id','foundation_id'];
}
