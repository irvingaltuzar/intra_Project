<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubSeccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_seccion', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('seccion_id');
            $table->foreign('seccion_id')->references('id')->on('seccion')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->integer('order')->nullable();
            $table->string('url')->nullable();
            $table->string('table_data')->nullable();
            $table->string('icon')->nullable();
            $table->string('denied')->nullable();
            $table->boolean('hidden')->default(0);
            $table->boolean('public')->default(1);
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
        Schema::dropIfExists('sub_seccion');
    }
}
