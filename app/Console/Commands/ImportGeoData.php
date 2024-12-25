<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;
use App\Models\Region;
use App\Models\City;

class ImportGeoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geo:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importer les pays, régions et villes depuis un fichier JSON';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = storage_path('app/json/geonames.json');
        if (!file_exists($filePath)) {
            $this->error('Le fichier JSON est introuvable.');
            return;
        }

        $data = json_decode(file_get_contents($filePath), true);

        foreach ($data as $countryData) {
            $country = Country::updateOrCreate(
                ['id' => $countryData['id']],
                [
                    'name' => $countryData['name'],
                    'iso3' => $countryData['iso3'],
                    'iso2' => $countryData['iso2'],
                    'phone_code' => $countryData['phone_code'],
                    'currency' => $countryData['currency'],
                ]
            );

            foreach ($countryData['states'] as $regionData) {
                $region = Region::updateOrCreate(
                    ['id' => $regionData['id']],
                    [
                        'name' => $regionData['name'],
                        'state_code' => $regionData['state_code'],
                        'country_id' => $country->id,
                    ]
                );

                foreach ($regionData['cities'] as $cityData) {
                    City::updateOrCreate(
                        ['id' => $cityData['id']],
                        [
                            'name' => $cityData['name'],
                            'region_id' => $region->id,
                        ]
                    );
                }
            }
        }

        $this->info('Importation terminée avec succès !');
    }
}
