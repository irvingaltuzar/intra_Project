<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributionListUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribution_list_users', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('distribution_list_id');
            $table->foreign('distribution_list_id')->references('id')->on('distribution_lists')->onDelete('cascade');
            $table->string('vw_users_usuario',100)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('distribution_list_users');
    }
}
