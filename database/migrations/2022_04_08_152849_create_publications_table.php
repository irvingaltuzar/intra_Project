<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('publications_section_id');
            $table->string('vw_users_usuario',100)->index();
            $table->string('aux_key_publication',100)->nullable();
            $table->text('description')->nullable();
            $table->string('title',255);
            $table->foreign('publications_section_id')->references('id')->on('publications_section');
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
        Schema::dropIfExists('publications');
    }
}
