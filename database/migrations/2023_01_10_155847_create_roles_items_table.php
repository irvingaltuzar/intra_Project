<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_items', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('roles_id');
            $table->integer('sub_seccion_id');
            $table->string('actions');
            $table->foreign('roles_id')->references('id')->on('roles')->onDelete('cascade');
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
        Schema::dropIfExists('roles_items');
    }
}
