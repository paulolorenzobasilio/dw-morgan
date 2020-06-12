<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CovidObservation extends Model
{
    protected $fillable = [
        'id', 'observation_date', 'province_state', 'country_region',
        'confirmed', 'deaths', 'recovered', 'last_update'
    ];

    public $timestamps = false;
}
