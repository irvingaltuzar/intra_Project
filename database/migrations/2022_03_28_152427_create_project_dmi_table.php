<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectDmiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_dmi', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('vw_locations_id')->nullable();
            $table->string('name',255)->nullable();
            $table->string('description',255)->nullable();
            $table->string('creation_date',50)->nullable();
            $table->integer('files_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_dmi');
    }
}
