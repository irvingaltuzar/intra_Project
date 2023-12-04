<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenssagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menssages', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->text('message');
            $table->integer('issuing_user')->nullable();
            $table->integer('receiver_user')->nullable();
            $table->string('type')->nullable();
            $table->boolean('read')->default(0);
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
        Schema::dropIfExists('menssages');
    }
}
