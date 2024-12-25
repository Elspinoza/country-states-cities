<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        // 'user_id', 
        'country_id', 
        'region_id', 
        'city_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
