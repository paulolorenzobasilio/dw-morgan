<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CovidObservationController extends Controller
{
    public function __invoke()
    {
        $covidObservations =  DB::table('covid_observations')
            ->select(
                [
                    'observation_date', 'country', DB::raw('SUM(confirmed) as confirmed'),
                    DB::raw('SUM(deaths) as deaths'), DB::raw('SUM(recovered) as recovered')
                ]
            )
            ->when(request('observation_date', false), function ($q, $observationDate) {
                return $q->whereObservationDate($observationDate);
            })
            ->groupBy(['observation_date', 'country'])
            ->orderByDesc(DB::raw('SUM(confirmed)'))
            ->limit(request('max_results', 15))
            ->get();
        
        return $this->groupBy('observation_date', $covidObservations);

        /**
         * TODO: How to serialize this
         * {
         * "2020-01-22": [
         *      {
         *          "country_region": "Mainland China",
         *          "confirmed": 547,
         *          "deaths": 17,
         *          "recovered: 28
         *      }
         *  ]
         * }
         * 
         * into this..
         * 
         * {
         * "observation_date": "2020-01-22"
         * "countries": [
         *      {
         *          "country": "Mainland China",
         *          "confirmed": 15
         *          "deaths": 2,
         *          "recovered": 7
         *      },
         *      {
         *          ...
         *      }
         *  ]
         * }
         */
    }


    private function groupBy(string $key, Collection $data): array
    {
        $result = [];
        foreach ($data as $val) {
            $temp = clone $val;
            unset($temp->$key);
            $result[$val->$key][] = $temp;
        }

        return $result;
    }
}
