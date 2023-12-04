<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommuniqueDistributionListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communique_distribution_lists', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('distribution_list_id');
            $table->foreign('distribution_list_id')->references('id')->on('distribution_lists')->onDelete('cascade');
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
        Schema::dropIfExists('communique_distribution_lists');
    }
}
