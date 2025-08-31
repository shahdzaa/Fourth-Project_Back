<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Neighbourhood extends Model
{
    use HasFactory;

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
    public function buildings()
    {
        return $this->hasMany(Building::class);
    }
    public function committees()
    {
        return $this->belongsToMany(Committee::class, 'committee_neighbourhood');
    }
    protected $fillable = [
        'name',
        'sector_id'
    ];

}
