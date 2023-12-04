<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConmmemorativeDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conmmemorative_date', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('title',255)->nullable()->index();
            $table->string('photo',255)->nullable();
            $table->text('description')->nullable()->index();
            $table->date('publication_date')->nullable()->index();
            $table->date('expiration_date')->nullable()->index();
            $table->foreign('locations_id')->references('id')->on('vw_locations');
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
        Schema::dropIfExists('news');
    }
}
