<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("first_name");
            $table->string("last_name")->nullable();
            $table->text("contact_email")->nullable();
            $table->string("phone_number_one")->nullable();
            $table->string("phone_number_two")->nullable();
            $table->text("dni");

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
        Schema::dropIfExists('clients');
    }
}
