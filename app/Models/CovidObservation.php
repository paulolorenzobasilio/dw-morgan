<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CovidObservation extends Model
{
    protected $fillable = [
        'id', 'observation_date', 'province_state', 'country', 'confirmed',
        'deaths', 'recovered', 'last_update'
    ];

    public $timestamps = false;

    public function topConfirmed($observationDate = false, $maxResult = 15)
    {
        return $this->select([
            'observation_date', 'country', DB::raw('SUM(confirmed) as confirmed'),
            DB::raw('SUM(deaths) as deaths'), DB::raw('SUM(recovered) as recovered')
        ])
            ->when($observationDate, function ($q, $observationDate) {
                return $q->whereObservationDate($observationDate);
            })
            ->groupBy(['observation_date', 'country'])
            ->when(!$observationDate, function ($q) {
                return $q->orderByDesc('observation_date');
            })
            ->orderByDesc(DB::raw('SUM(confirmed)'))
            ->limit($maxResult)
            ->get();
    }
}
