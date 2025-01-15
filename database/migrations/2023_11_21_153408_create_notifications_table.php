<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('usuario_notifying',100)->index();
            $table->string('usuario_notified',100)->index();
            $table->dateTime('read_at')->nullable(true);
            $table->string('message')->nullable(true);
            $table->string('type');
            $table->string('data')->nullable(true);
            $table->string('link')->nullable(true);
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
        Schema::dropIfExists('notifications');
    }
}
