<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnterpriseCountrysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprise_countries', function (Blueprint $table) {
            $table->bigIncrements("id");

            $table->bigInteger("enterprise_id")->unsigned();
            $table->integer("country_id")->unsigned();

            $table->foreign("enterprise_id")->references("id")->on("enterprises")->onDelete("cascade");
            $table->foreign("country_id")->references("id")->on("countries")->onDelete("cascade");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enterprise_countries');
    }
}
