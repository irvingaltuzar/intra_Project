<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommuniquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communiques', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('title')->index();
            $table->string('priority')->nullable()->index();
            $table->date('expiration_date')->nullable()->index();
            $table->string('link',255)->nullable();
            $table->text('description')->nullable()->index();
            $table->string('photo',255)->nullable();
            $table->string('video',255)->nullable();
            $table->string('type')->nullable();
            $table->integer('user_create_id')->nullable();
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
        Schema::dropIfExists('communiques');
    }
}
