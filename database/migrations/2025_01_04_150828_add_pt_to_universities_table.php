<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPtToUniversitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('universitas', function (Blueprint $table) {
            $table->string('pt')->nullable(); // Add the pt column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('universitas', function (Blueprint $table) {
            $table->dropColumn('pt'); // Remove the pt column
        });
    }
}
