<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivateCommuniquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('private_communiques', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('vw_users_usuario',100)->index();
            $table->integer('communique_id');
            $table->foreign('communique_id')->references('id')->on('communiques')->onDelete('cascade');
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
        Schema::dropIfExists('private_communiques');
    }
}
