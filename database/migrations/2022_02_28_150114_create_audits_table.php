<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('sub_seccion_id');
            $table->foreign('sub_seccion_id')->references('id')->on('sub_seccion')->onDelete('cascade');
            $table->string('username');
            $table->date('date_audit');
            $table->string('ip');
            $table->string('event');
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
        Schema::dropIfExists('audits');
    }
}
