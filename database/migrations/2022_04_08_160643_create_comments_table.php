<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('publications_id');
            $table->string('vw_users_usuario',100)->nullable(false)->index();
            $table->text('comment')->nullable();
            $table->integer('parent_comments_id')->nullable(true);
            $table->foreign('publications_id')->references('id')->on('publications');
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
        Schema::dropIfExists('comments');
    }
}
