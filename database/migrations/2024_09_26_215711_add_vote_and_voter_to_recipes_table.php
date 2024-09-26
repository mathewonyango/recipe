<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->integer('vote')->default(0); // Add a vote column
            $table->string('voter')->nullable();  // Add a voter column
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn('vote');
            $table->dropColumn('voter');
        });
    }

};
