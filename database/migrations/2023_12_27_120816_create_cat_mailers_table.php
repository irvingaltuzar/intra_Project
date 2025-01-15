<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatMailersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_mailers', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('mail_mailer',50);
            $table->string('mail_host',100);
            $table->string('mail_port',10);
            $table->string('mail_username',255);
            $table->string('mail_password',255);
            $table->string('mail_encryption',50);
            $table->string('assigned_to_department',50);
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
        Schema::dropIfExists('cat_mailers');
    }
}
