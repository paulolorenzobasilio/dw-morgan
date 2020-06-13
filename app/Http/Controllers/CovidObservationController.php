<?php

namespace App\Http\Controllers;

use App\Models\CovidObservation;

class CovidObservationController extends Controller
{
    private $covidObservation;

    public function __construct(CovidObservation $covidObservation)
    {
        $this->covidObservation = $covidObservation;
    }

    public function __invoke()
    {
        $observationDate = request()->query('observation_date', false);
        $maxResults = request()->query('max_results', 15);

        $covidObservations = $this->covidObservation->topConfirmed($observationDate, $maxResults);

        if ($observationDate) {
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
    private function buildSingleResultSet($data): array
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
    private function buildMultipleResultSet($data): array
    {
        $results = [];
        $data = $data->groupBy('observation_date');
        foreach ($data as $val) {
            $results[] = $this->buildSingleResultSet($val);
        }
        return $results;
    }
}
