<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('type_event_id')->nullable(false);
            $table->string('title',255)->nullable(false)->index();
            $table->string('place',255)->nullable(false);
            $table->text('description');
            $table->string('photo',255);
            $table->date('date')->index();
            $table->string('time',45);
            $table->foreign('type_event_id')->references('id')->on('type_event')->onDelete('cascade');
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
        Schema::dropIfExists('events');
    }
}
