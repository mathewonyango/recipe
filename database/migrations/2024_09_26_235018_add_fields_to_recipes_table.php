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
            // Allowing NULL values for these new columns initially
            $table->string('image')->nullable()->after('status'); // Image field
            $table->string('tags')->nullable()->after('image'); // Tags field
            $table->enum('difficulty_level', ['easy', 'medium', 'hard'])->nullable()->after('tags'); // Difficulty level field
            $table->text('nutritional_information')->nullable()->after('difficulty_level'); // Nutritional information field
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('tags');
            $table->dropColumn('difficulty_level');
            $table->dropColumn('nutritional_information');
        });
    }
};
