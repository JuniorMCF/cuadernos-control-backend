<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements("id");

            $table->bigInteger("product_id")->unsigned();
            $table->bigInteger("client_id")->unsigned();
            $table->bigInteger("enterprise_id")->unsigned();

            $table->decimal("price_actual",6,2)->unsigned();
            $table->integer("quantity")->unsigned();
            $table->text("tax")->nullable();
            $table->decimal("tax_aplicated",4,2)->default(0);
            $table->decimal("discount",6,2)->default(0);
            $table->decimal("registered_amount",6,2)->unsigned();

            $table->string("status")->default("Pendiente");//cancelado

            $table->foreign("product_id")->references("id")->on("products")->onDelete("cascade");
            $table->foreign("client_id")->references("id")->on("clients")->onDelete("cascade");
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
        Schema::dropIfExists('sales');
    }
}
