<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectBoardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_board', function (Blueprint $table) {
            $table->integer("id")->autoIncrement();
            $table->string("name");
            $table->integer("project_board_categories_id");
            $table->string("owner_usuario")->nullable()->index();
            $table->string("leader_usuario")->nullable()->index();
            $table->string("status")->nullable();
            $table->float("progress",3,2)->nullable();
            $table->date('date')->nullable()->index();
            $table->string("description")->nullable();
            $table->foreign('project_board_categories_id')->references('id')->on('project_board_categories')->onDelete('cascade');
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
        Schema::dropIfExists('project_board');
    }
}
