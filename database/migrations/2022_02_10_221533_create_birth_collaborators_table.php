<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBirthCollaboratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('birth_collaborators', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->date('birth')->index();
            $table->string('sex')->nullable();
            $table->text('message');
            $table->string('vw_users_usuario',100)->nullable()->index();
            $table->string('collaborator',255)->nullable();
            $table->integer('templante_collaborator_id');
            $table->foreign('templante_collaborator_id')->references('id')->on('templante_collaborators')->onDelete('cascade');
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
        Schema::dropIfExists('birth_collaborators');
    }
}
