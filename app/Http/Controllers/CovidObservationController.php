<?php

namespace App\Http\Controllers;

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
            ->limit(request()->query('max_results', 15))
            ->get();

        if (request('observation_date', false)) {
            return $this->buildSingleResult($covidObservations);
        }

        return $this->buildMultipleResult($covidObservations);
    }

    /**
     * Construct the structure by
     * {
     * observation_date: "2020-01-22"
     * countries: [...]
     * }
     */
    private function buildSingleResult($data)
    {
        foreach ($data as $val) {
            $results["observation_date"] = $val->observation_date;
            unset($val->observation_date);
            $results["countries"][] = $val;
        }
        return $results;
    }

    /**
     * Construct the structure by multiple set
     * {
     *      [
     *          observation_date: "2020-01-22"
     *          countries: [...]
     *      ]
     *      [
     *          observation_date: "2020-01-23"
     *          countries: [...]
     *      ]
     * }
     */
    private function buildMultipleResult($data)
    {
        $results = [];
        $data = $data->groupBy('observation_date');
        foreach ($data as $key => $val) {
            $results [] = $this->buildSingleResult($val);
        }
        return $results;
    }
}
