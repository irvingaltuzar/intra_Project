<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectDmiItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_dmi_item', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('files_id')->nullable();
            $table->string('section',100)->nullable();
            $table->text('description')->nullable();
            $table->integer('project_dmi_id');
            $table->foreign('project_dmi')->references('id')->on('project_dmi');
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
        Schema::dropIfExists('project_dmi_item');
    }
}
