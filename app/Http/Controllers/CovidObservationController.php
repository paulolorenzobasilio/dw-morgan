<?php

namespace App\Http\Controllers;

use App\Models\CovidObservation;
use Illuminate\Support\Facades\DB;

class CovidObservationController extends Controller
{
    private $covidObservation;

    public function __construct(CovidObservation $covidObservation)
    {
        $this->covidObservation = $covidObservation;
    }

    public function __invoke()
    {
        $covidObservations =  $this->covidObservation->select([
            'observation_date', 'country', DB::raw('SUM(confirmed) as confirmed'),
            DB::raw('SUM(deaths) as deaths'), DB::raw('SUM(recovered) as recovered')
        ])->when(request('observation_date', false), function ($q, $observationDate) {
            return $q->whereObservationDate($observationDate);
        })->groupBy(['observation_date', 'country'])
            ->orderByDesc(DB::raw('SUM(confirmed)'))
            ->limit(request()->query('max_results', 15))
            ->get();

        if (request('observation_date', false)) {
            return $this->buildSingleResultSet($covidObservations);
        }

        return $this->buildMultipleResultSet($covidObservations);
    }

    /**
     * Construct the structure by object
     * {
     *      observation_date: "2020-01-22"
     *      countries: [...]
     * }
     */
    private function buildSingleResultSet($data)
    {
        $results = [];
        foreach ($data as $val) {
            $results["observation_date"] = $val->observation_date;
            unset($val->observation_date);
            $results["countries"][] = $val;
        }
        return $results;
    }

    /**
     * Construct the structure by array of singleResultSet
     * {
     *      [
     *          observation_date: "2020-01-22"
     *          countries: [...]
     *      ]
     *      [...]
     * }
     */
    private function buildMultipleResultSet($data)
    {
        $results = [];
        $data = $data->groupBy('observation_date');
        foreach ($data as $val) {
            $results[] = $this->buildSingleResultSet($val);
        }
        return $results;
    }
}
