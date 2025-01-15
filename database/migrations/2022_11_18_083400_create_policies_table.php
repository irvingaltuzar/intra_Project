<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('title',255)->nullable()->index();
            $table->string('photo',255)->nullable();
            $table->string('video',255)->nullable();
            $table->string('link',500)->nullable();
            $table->text('description')->nullable();
            $table->date('expiration_date')->nullable()->index();
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
        Schema::dropIfExists('policies');
    }
}
