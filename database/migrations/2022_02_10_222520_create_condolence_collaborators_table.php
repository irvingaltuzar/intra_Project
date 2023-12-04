<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCondolenceCollaboratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('condolence_collaborators', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->date('condolence_date')->index();
            $table->date('expiration_date')->nullable();
            $table->string('accompanies',255)->nullable()->index();
            $table->string('condolence',255)->nullable()->index();
            $table->integer('vw_users_usuario')->index();
            $table->string('collaborator',255)->nullable()->index();
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
        Schema::dropIfExists('condolence_collaborators');
    }
}
