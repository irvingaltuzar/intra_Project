<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessUnitCommuniquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_unit_communiques', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('business_unit_id');
            $table->foreign('business_unit_id')->references('id')->on('business_units')->onDelete('cascade');
            $table->integer('communique_id');
            $table->foreign('communique_id')->references('id')->on('communiques')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_unit_communiques');
    }
}
