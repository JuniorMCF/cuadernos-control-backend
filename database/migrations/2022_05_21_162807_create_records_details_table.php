<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records_details', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->text("comment")->nullable();
            $table->decimal("amount",6,2)->default(0);
            $table->bigInteger("record_id")->unsigned();
            $table->bigInteger("client_id")->unsigned();

            $table->foreign("record_id")->references("id")->on("records")->onDelete("cascade");
            $table->foreign("client_id")->references("id")->on("clients")->onDelete("cascade");
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
        Schema::dropIfExists('records_details');
    }
}
