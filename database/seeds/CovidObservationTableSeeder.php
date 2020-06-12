<?php

use App\Models\CovidObservation;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class CovidObservationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!ini_get("auto_detect_line_endings")) {
            ini_set("auto_detect_line_endings", '1');
        }
        
        $covidObservation = new CovidObservation();

        $csv = Reader::createFromPath(__DIR__ . '/data/covid_19_data.csv', 'r');
        $csv->SetHeaderOffset(0);
        foreach ($csv->getRecords() as $record) {
            $covidObservation->create([
                'id' => $record['SNo'],
                'observation_date' => $record['ObservationDate'],
                'province_state' => $record['Province/State'],
                'country_region' => $record['Country/Region'],
                'last_update' => $record['Last Update'],
                'confirmed' => (int) $record['Confirmed'],
                'deaths' => (int) $record['Deaths'],
                'recovered' => (int) $record['Recovered']
            ]);
        }
    }
}
