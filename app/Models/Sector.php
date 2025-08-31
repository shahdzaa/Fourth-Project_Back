<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Sector extends Model
{
    use HasFactory;

    public function neighbourhoods()
    {
        return $this->hasMany(Neighbourhood::class);
    }
    protected $fillable=['name'];
}
