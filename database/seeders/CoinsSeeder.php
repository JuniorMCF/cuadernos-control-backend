<?php

namespace Database\Seeders;

use App\Models\Coin;
use Illuminate\Database\Seeder;
use File;

class CoinsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $json = File::get("database/data/coins.json");
        $coins = json_decode($json,1);

        foreach ($coins as $key => $value) {
            //\Log::debug($value);
            if ($key == "coins") {
                $coins_1 = $value;
                foreach ($coins_1 as $key_1 => $value_1) {
                    $c = new Coin();
                    $coin = $value_1;
                    foreach($coin as $key => $value){
                        if ($key == "moneda") {
                            $c->coin = $value;
                        }
                        if ($key == "simbolo de dinero") {
                            $c->symbol = $value;
                        }
                        if ($key == "código") {
                            $c->code = $value;
                        }
                        if ($key == "nombre") {
                            $c->name = $value;
                        }
                    }
                    $c->save();

                }

            }
        }
    }
}
