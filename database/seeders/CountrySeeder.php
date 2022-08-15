<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use File;
class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $json = File::get("database/data/country.json");
        $countrys = json_decode($json,1);

        foreach ($countrys as $key => $value) {
            //\Log::debug($value);
            if ($key == "countrys") {
                $country_1 = $value;
                foreach ($country_1 as $key_1 => $value_1) {
                    $c = new Country();
                    $country = $value_1;
                    foreach($country as $key => $value){
                        if ($key == "name") {
                            $c->name = $value;
                        }
                        if ($key == "code") {
                            $c->code = $value;
                        }
                    }
                    $c->save();

                }

            }
        }
    }
}
