<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnterprisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprises', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->text("logo")->nullable();
            $table->text("name")->nullable();
            $table->text("address")->nullable();
            $table->string("latitude")->nullable();
            $table->string("longitude")->nullable();
            $table->text("ruc")->nullable();


            $table->integer("country_id")->unsigned()->nullable();
            $table->integer("coin_id")->unsigned()->nullable();
            $table->bigInteger("user_id")->unsigned();

            $table->foreign("country_id")->references("id")->on("countries")->onDelete("cascade");
            $table->foreign("coin_id")->references("id")->on("coins")->onDelete("cascade");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");


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
        Schema::dropIfExists('enterprises');
    }
}
