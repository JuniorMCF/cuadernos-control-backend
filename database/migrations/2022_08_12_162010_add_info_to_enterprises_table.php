<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInfoToEnterprisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enterprises', function (Blueprint $table) {
            //

            $table->string('email')->nullable();
            $table->string('dpto')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('phone_contact_one')->nullable();
            $table->string('phone_contact_two')->nullable();
            $table->string('banco')->nullable();
            $table->string('nro_cta')->nullable();
            $table->string('propietary')->nullable();//propietary number account


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enterprises', function (Blueprint $table) {
            //
            $table->dropColumn('email');
            $table->dropColumn('dpto');
            $table->dropColumn('province');
            $table->dropColumn('district');
            $table->dropColumn('phone_contact_one');
            $table->dropColumn('phone_contact_two');
            $table->dropColumn('banco');
            $table->dropColumn('nro_cta');
            $table->dropColumn('propietary');

        });
    }
}
