<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('vw_users_usuario',100)->nullable(false)->index();
            $table->string('vw_users_usuario_top',100)->nullable(false)->index();
            $table->string('new_position_company',255);
            $table->string('photo',255)->nullable();
            $table->text('message')->nullable();
            $table->date('expiration_date')->nullable();
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
        Schema::dropIfExists('promotions');
    }
}
