<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_sessions', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('temp_user');
            $table->string('temp_password');
            $table->string('personal_token')->nullable();
            $table->boolean('is_login')->default(0);
            $table->boolean('is_expired')->default(0);
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
        Schema::dropIfExists('temp_sessions');
    }
}
