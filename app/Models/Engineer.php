<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Engineer extends Model
{
    use HasFactory;

    public function committees()
    {
        return $this->belongsToMany(Committee::class, 'committee_engineer')->withPivot('is_manager');
    }
    protected $fillable=['first_name','second_name','phone_number','address','age','specialization'];
}
