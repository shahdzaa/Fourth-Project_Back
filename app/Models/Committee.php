<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Committee extends Model
{
    use HasFactory;

    public function neighbourhoods()
    {
        return $this->belongsToMany(Neighbourhood::class, 'committee_neighbourhood');
    }
    public function engineers()
    {
        return $this->belongsToMany(Engineer::class, 'committee_engineer')->withPivot('is_manager');
    }
    public function damagReports()
    {
        return $this->hasMany(DamageReport::class, 'committee_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    protected $fillable=['user_id'];

}
