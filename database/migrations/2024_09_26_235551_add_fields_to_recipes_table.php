<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up()
    // {
    //     Schema::table('recipes', function (Blueprint $table) {
    //         $table->string('image')->nullable(false)->change();
    //         $table->string('tags')->nullable(false)->change();
    //         $table->enum('difficulty_level', ['easy', 'medium', 'hard'])->nullable(false)->change();
    //     });
    // }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
            $table->string('tags')->nullable()->change();
            $table->enum('difficulty_level', ['easy', 'medium', 'hard'])->nullable()->change();
        });
    }
};
