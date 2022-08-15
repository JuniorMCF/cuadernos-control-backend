<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("name");
            $table->text("description")->nullable();
            $table->decimal("price",6,2)->default(0);
            $table->boolean("is_offer")->default(false);
            $table->decimal("price_offer",6,2)->default(0);

            $table->string("status")->default(0);//0 : disponible  1: no disponible

            $table->bigInteger("enterprise_id")->unsigned();
            $table->foreign("enterprise_id")->references("id")->on("enterprises")->onDelete("cascade");
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
        Schema::dropIfExists('services');
    }
}
