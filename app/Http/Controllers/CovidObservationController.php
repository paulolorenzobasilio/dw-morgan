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
                    'observation_date', 'country_region', DB::raw('SUM(confirmed) as confirmed'),
                    DB::raw('SUM(deaths) as deaths'), DB::raw('SUM(recovered) as recovered')
                ]
            )
            ->when(request('observation_date', false), function ($q, $observationDate) {
                return $q->whereObservationDate($observationDate);
            })
            ->groupBy(['observation_date', 'country_region'])
            ->orderByDesc(DB::raw('SUM(confirmed)'))
            ->limit(request('max_results', 15))
            ->get();

        return $this->groupBy('observation_date', $covidObservations);
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
