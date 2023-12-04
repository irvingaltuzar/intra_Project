<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommuniqueFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communique_files', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('file_id');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            $table->integer('communique_id');
            $table->foreign('communique_id')->references('id')->on('communiques')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('communique_files');
    }
}
