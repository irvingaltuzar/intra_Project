<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBackgroundColorToTemplanteCollaboratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('templante_collaborators', function (Blueprint $table) {
            $table->string('background_color',20)->after('type')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('templante_collaborators', function (Blueprint $table) {
            $table->dropColumn('background_color');
        });
    }
}
