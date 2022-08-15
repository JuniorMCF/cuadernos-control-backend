<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnterpriseCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprise_coins', function (Blueprint $table) {
            $table->bigIncrements("id");

            $table->bigInteger("enterprise_id")->unsigned();
            $table->integer("coin_id")->unsigned();

            $table->foreign("enterprise_id")->references("id")->on("enterprises")->onDelete("cascade");
            $table->foreign("coin_id")->references("id")->on("coins")->onDelete("cascade");

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
        Schema::dropIfExists('enterprise_coins');
    }
}
