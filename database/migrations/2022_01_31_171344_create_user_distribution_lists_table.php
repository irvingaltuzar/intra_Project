<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDistributionListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_distribution_lists', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('vw_users_usuario',100)->index();
            $table->integer('distribution_list_id');
            $table->foreign('distribution_list_id')->references('id')->on('distribution_lists')->onDelete('cascade');
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
        Schema::dropIfExists('user_distribution_lists');
    }
}
