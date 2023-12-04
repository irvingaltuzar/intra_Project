<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBucketLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bucket_locations', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('origin_record_id')->index();
            $table->integer('locations_id')->index();
            $table->integer('sub_seccion_id')->index();
            $table->foreign('sub_seccion_id')->references('id')->on('sub_seccion')->onDelete('cascade');
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
        Schema::dropIfExists('event_files');
    }
}
