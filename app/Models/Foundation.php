<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Foundation extends Model
{
    use HasFactory;
    public function damageReports()
    {

        return $this->hasMany(DamageReport::class);
    }
    protected $fillable=['type'];
}
